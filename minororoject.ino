#include <ESP8266WiFi.h>
#include "Adafruit_MQTT.h"
#include "Adafruit_MQTT_Client.h"
//adafruit library 
#include <Adafruit_Fingerprint.h>
#include <ESP8266HTTPClient.h>
#include <ESP8266WiFiMulti.h>
#include <WiFiClientSecureBearSSL.h>

/************************* WiFi Access Point *********************************/
#define WLAN_SSID       "password11"  //wifi name
#define WLAN_PASS       "X7A9WZHEYJC+L6S"  //wifi password

/************************* Adafruit.io Setup *********************************/

#define AIO_SERVER      "io.adafruit.com"
#define AIO_SERVERPORT  1883                   // use 8883 for SSL
#define AIO_USERNAME    "ganesh333"             //ada fruit io ma vayo username
#define AIO_KEY         "aio_Kkui38HSRAHPSyCZ167wn0IgSQfA"  // adafruit ma vako token key

/************ Global State (you don't need to change this!) ******************/
const uint8_t fingerprint[20] = {0xbe, 0x41, 0xb4, 0x01, 0x12, 0x48, 0xbf, 0x7e, 0x04, 0xc1, 0x38, 0xa8, 0xd7, 0x4c, 0xb8, 0x50, 0xf3, 0xe2, 0x30, 0xca};
ESP8266WiFiMulti WiFiMulti;
// Create an ESP8266 WiFiClient class to connect to the MQTT server.
WiFiClient clients;

// or... use WiFiClientSecure for SSL
//WiFiClientSecure client;

// Setup the MQTT client class by passing in the WiFi client and MQTT server and login details.
Adafruit_MQTT_Client mqtt(&clients, AIO_SERVER, AIO_SERVERPORT, AIO_USERNAME, AIO_KEY);

/****************************** Feeds ***************************************/
// Setup a feed called 'onoff' for subscribing to changes.
Adafruit_MQTT_Subscribe onoffregister = Adafruit_MQTT_Subscribe(&mqtt, AIO_USERNAME "/feeds/register");
Adafruit_MQTT_Subscribe enrollid = Adafruit_MQTT_Subscribe(&mqtt, AIO_USERNAME "/feeds/enrollid");

void MQTT_connect();
SoftwareSerial mySerial(D5, D6);

Adafruit_Fingerprint finger = Adafruit_Fingerprint(&mySerial);
uint8_t id;
int counter = 0;

void setup() {
  Serial.begin(115200);
  delay(1000);
  pinMode(LED_BUILTIN, OUTPUT);  

// ----- fingerprint setup ---------
  // set the data rate for the sensor serial port
  finger.begin(57600);

  if (finger.verifyPassword()) {
    Serial.println("Found fingerprint sensor!");
  } else {
    Serial.println("Did not find fingerprint sensor :(");
    while (1) { delay(3000); return  ; }
  }

  Serial.println(F("Reading sensor parameters"));
  finger.getParameters();
  Serial.print(F("Status: 0x")); Serial.println(finger.status_reg, HEX);
  Serial.print(F("Sys ID: 0x")); Serial.println(finger.system_id, HEX);
  Serial.print(F("Capacity: ")); Serial.println(finger.capacity);
  Serial.print(F("Security level: ")); Serial.println(finger.security_level);
  Serial.print(F("Device address: ")); Serial.println(finger.device_addr, HEX);
  Serial.print(F("Packet len: ")); Serial.println(finger.packet_len);
  Serial.print(F("Baud rate: ")); Serial.println(finger.baud_rate);

// --------- Fingerprint set up end -------------

//-- ------- Mqtt setup-----  
  // Connect to WiFi access point.
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(WLAN_SSID);
  
//  WiFi.mode(WIFI_STA);
//  WiFiMulti.addAP("password11", "X7A9WZHEYJC+L6S");
  WiFi.begin(WLAN_SSID, WLAN_PASS);
  while (WiFi.status() != WL_CONNECTED) {
       delay(500);
       Serial.print(".");
     }
     Serial.println();

   Serial.println("WiFi connected");
   Serial.println("IP address: "); Serial.println(WiFi.localIP());

  // Setup MQTT subscription for onoff feed.
  mqtt.subscribe(&onoffregister);
  mqtt.subscribe(&enrollid);

// ----------mqtt setup end --------
}

uint32_t x=0;
String messages = " alert messages to be sent to database";

void loop() {
  
  // Ensure the connection to the MQTT server is alive (this will make the first
  // connection and automatically reconnect when disconnected).  See the MQTT_connect
  // function definition further below.
  MQTT_connect();

  Adafruit_MQTT_Subscribe *subscription;
  while ((subscription = mqtt.readSubscription(5000))) {
    if (subscription == &onoffregister) {
      Serial.print(F("Got: "));
      Serial.println((char *)onoffregister.lastread);
  
    String onbotton = (char *)onoffregister.lastread; 
      if(onbotton == "ON"){  // if register feed in adafruit io is ON then below code will run
           digitalWrite(LED_BUILTIN, LOW);   // Turn the LED on (Note that LOW is the voltage level
             // but actually the LED is on; this is because
             // it is active low on the ESP-01)
          delay(1000);                      // Wait for a second
          digitalWrite(LED_BUILTIN, HIGH);  // Turn the LED off by making the voltage HIGH
          delay(100);
          
          Serial.println(" Register is on and idstring");
          
          String idstring = (char *)enrollid.lastread; // read the data from enroll id feed of adafruit io 
          Serial.println(idstring);
          id = idstring.toInt();           // char to int conversion
          Serial.println("id is : ");
          Serial.println(id);
          if(id > 0){
             while (!  getFingerprintEnroll() );   //Enrolling the finger function .... 
            }
          
         }
         if(onbotton == "OFF"){           // if register feed in adafruit io is ON then below code will run... not needed as just for debugging or checking 
            Serial.println("Register is off");           
          }
    
    } // end of if subscription code 
   

  
  }    //  end while scriotion code
  Serial.println("inside loop ");       
  
  getFingerprintID();               //--- fingerprint matching function ... runs every 5 seconds 
} // end of loop


// Function to connect and reconnect as necessary to the MQTT server.
// Should be called in the loop function and it will take care if connecting.
void MQTT_connect() {
  int8_t ret;

  // Stop if already connected.
  if (mqtt.connected()) {
    return;
  }

  Serial.print("Connecting to MQTT... ");

  uint8_t retries = 3;
  while ((ret = mqtt.connect()) != 0) { // connect will return 0 for connected
       Serial.println(mqtt.connectErrorString(ret));
       Serial.println("Retrying MQTT connection in 5 seconds...");
       mqtt.disconnect();
       delay(5000);  // wait 5 seconds
       retries--;
       if (retries == 0) {
         // basically die and wait for WDT to reset me
         while (1);
       }
  }
  Serial.println("MQTT Connected!");
}


//----------------------- ENROLL FINGERPRINT FUNCTION

uint8_t getFingerprintEnroll() {

  int p = -1;
  Serial.print("Waiting for valid finger to enroll as #"); Serial.println(id);
  messages="Please! Scan your finger";
  send_message_to_database(messages,0);
  while (p != FINGERPRINT_OK) {
    p = finger.getImage();
    switch (p) {
    case FINGERPRINT_OK:
      Serial.println("Image taken");
      break;
    case FINGERPRINT_NOFINGER:
      Serial.println(".");
      break;
    case FINGERPRINT_PACKETRECIEVEERR:
      Serial.println("Communication error");
      break;
    case FINGERPRINT_IMAGEFAIL:
      Serial.println("Imaging error");
      break;
    default:
      Serial.println("Unknown error");
      break;
    }
  }

  // OK success!

  p = finger.image2Tz(1);
  switch (p) {
    case FINGERPRINT_OK:
      Serial.println("Image converted");
      
      break;
    case FINGERPRINT_IMAGEMESS:
      Serial.println("Image too messy");
      return p;
    case FINGERPRINT_PACKETRECIEVEERR:
      Serial.println("Communication error");
      return p;
    case FINGERPRINT_FEATUREFAIL:
      Serial.println("Could not find fingerprint features");
      return p;
    case FINGERPRINT_INVALIDIMAGE:
      Serial.println("Could not find fingerprint features");
      return p;
    default:
      Serial.println("Unknown error");
      return p;
  }

  Serial.println("Remove finger");
  messages="Remove finger and place it again";
  send_message_to_database(messages,0);

  
  p = 0;
  while (p != FINGERPRINT_NOFINGER) {
    p = finger.getImage();
  }
  Serial.print("ID "); Serial.println(id);
  p = -1;
  Serial.println("Place same finger again");
  while (p != FINGERPRINT_OK) {
    p = finger.getImage();
    switch (p) {
    case FINGERPRINT_OK:
      Serial.println("Image taken");
      break;
    case FINGERPRINT_NOFINGER:
      Serial.print(".");
      break;
    case FINGERPRINT_PACKETRECIEVEERR:
      Serial.println("Communication error");
      break;
    case FINGERPRINT_IMAGEFAIL:
      Serial.println("Imaging error");
      break;
    default:
      Serial.println("Unknown error");
      break;
    }
  }

  // OK success!

  p = finger.image2Tz(2);
  switch (p) {
    case FINGERPRINT_OK:
      Serial.println("Image converted");
      break;
    case FINGERPRINT_IMAGEMESS:
      Serial.println("Image too messy");
      return p;
    case FINGERPRINT_PACKETRECIEVEERR:
      Serial.println("Communication error");
      return p;
    case FINGERPRINT_FEATUREFAIL:
      Serial.println("Could not find fingerprint features");
      return p;
    case FINGERPRINT_INVALIDIMAGE:
      Serial.println("Could not find fingerprint features");
      return p;
    default:
      Serial.println("Unknown error");
      return p;
  }

  // OK converted!
  Serial.print("Creating model for #");  Serial.println(id);
  
  p = finger.createModel();
  if (p == FINGERPRINT_OK) {
    Serial.println("Prints matched!");
  } else if (p == FINGERPRINT_PACKETRECIEVEERR) {
    Serial.println("Communication error");
    return p;
  } else if (p == FINGERPRINT_ENROLLMISMATCH) {
    Serial.println("Fingerprints did not match");
    messages="Fingerprints did not match. TRY AGAIN";
    send_message_to_database(messages,0);
    return p;
  } else {
    Serial.println("Unknown error");
    return p;
  }

  Serial.print("ID "); Serial.println(id);
  p = finger.storeModel(id);
  if (p == FINGERPRINT_OK) {
    Serial.println("Stored!");
    messages="Sucessfully Stored ";
    send_message_to_database(messages,0);
  } else if (p == FINGERPRINT_PACKETRECIEVEERR) {
    Serial.println("Communication error");
    return p;
  } else if (p == FINGERPRINT_BADLOCATION) {
    Serial.println("Could not store in that location");
    return p;
  } else if (p == FINGERPRINT_FLASHERR) {
    Serial.println("Error writing to flash");
    return p;
  } else {
    Serial.println("Unknown error");
    return p;
  }

  return true;
}

// -----------------CHECKING fingerprint function

uint8_t getFingerprintID() {
  
  Serial.println("matching fingerprint : ");

  uint8_t p = finger.getImage();
  switch (p) {
    case FINGERPRINT_OK:
      Serial.println("Image taken");
      break;
    case FINGERPRINT_NOFINGER:
      Serial.println("No finger detected");
      return p;
    case FINGERPRINT_PACKETRECIEVEERR:
      Serial.println("Communication error");
      return p;
    case FINGERPRINT_IMAGEFAIL:
      Serial.println("Imaging error");
      return p;
    default:
      Serial.println("Unknown error");
      return p;
  }

  // OK success!
  p = finger.image2Tz();
  switch (p) {
    case FINGERPRINT_OK:
      Serial.println("Image converted");
      break;
    case FINGERPRINT_IMAGEMESS:
      Serial.println("Image too messy");
      return p;
    case FINGERPRINT_PACKETRECIEVEERR:
      Serial.println("Communication error");
      return p;
    case FINGERPRINT_FEATUREFAIL:
      Serial.println("Could not find fingerprint features");
      return p;
    case FINGERPRINT_INVALIDIMAGE:
      Serial.println("Could not find fingerprint features");
      return p;
    default:
      Serial.println("Unknown error");
      return p;
  }

  // OK converted!
  p = finger.fingerSearch();
  if (p == FINGERPRINT_OK) {
    Serial.println("Found a print match!");    
    send_user_id_to_database(finger.fingerID);
    counter = 0;
  } else if (p == FINGERPRINT_PACKETRECIEVEERR) {
    Serial.println("Communication error");
    return p;
  } else if (p == FINGERPRINT_NOTFOUND) {
    Serial.println("Did not find a match");
    counter++;
    return p;
  } else {
    Serial.println("Unknown error");
    return p;
  }

  // found a match!
  Serial.print("Found ID #"); Serial.print(finger.fingerID);
  Serial.print(" with confidence of "); Serial.println(finger.confidence);

  return finger.fingerID;
}

// returns -1 if failed, otherwise returns ID #
int getFingerprintIDez() {
  uint8_t p = finger.getImage();
  if (p != FINGERPRINT_OK)  return -1;

  p = finger.image2Tz();
  if (p != FINGERPRINT_OK)  return -1;

  p = finger.fingerFastSearch();
  if (p != FINGERPRINT_OK)  return -1;

  // found a match!
  Serial.print("Found ID #"); Serial.print(finger.fingerID);
  Serial.print(" with confidence of "); Serial.println(finger.confidence);
  return finger.fingerID;
}


void send_message_to_database(String message,int alert_on){
    std::unique_ptr<BearSSL::WiFiClientSecure>client(new BearSSL::WiFiClientSecure);

    client->setFingerprint(fingerprint);
    HTTPClient https;

    Serial.print("[HTTPS] begin...\n");
    if(alert_on == 1){
       https.begin(*client, "https://websitename/minorproject/api/alert_message.php");
    }else {
      https.begin(*client, "https://websitename.com.np/minorproject/api/messageInsert.php");
      }
      Serial.print("[HTTPS] POST...\n");
     https.addHeader("Content-Type", "application/x-www-form-urlencoded");

    Serial.print("[HTTPs] POST...\n");
    int httpsCode = https.POST("auth_key=authkey&message="+message);

    // httpCode will be negative on error
    if (httpsCode > 0) {
      // HTTP header has been send and Server response header has been handled
      Serial.printf("[HTTP] POST... code: %d\n", httpsCode);

      // file found at server
      if (httpsCode == HTTP_CODE_OK) {
        const String& payload = https.getString();
        Serial.println("received payload:\n<<");
        Serial.println(payload);
        Serial.println(">>");
      }
      }else {
        Serial.printf("[HTTPS] GET... failed, error: %s\n", https.errorToString(httpsCode).c_str());
      }

      https.end();
   
}


void send_user_id_to_database(int message){
    std::unique_ptr<BearSSL::WiFiClientSecure>client(new BearSSL::WiFiClientSecure);

    client->setFingerprint(fingerprint);
    
    HTTPClient https;
    
    Serial.print("[HTTPS] begin...\n");
    if (https.begin(*client, "https://websitename/minorproject/api/unlock_table_ma_user_id_insert.php")) {  // HTTPS
      Serial.print("[HTTPS] POST...\n");
     https.addHeader("Content-Type", "application/x-www-form-urlencoded");

    Serial.print("[HTTPs] POST...\n");
    // start connection and send HTTP header and body
    int httpsCode = https.POST("auth_key=authkey&message=" + message);

    // httpsCode will be negative on error
    if (httpsCode > 0) {
      // HTTP header has been send and Server response header has been handled
      Serial.printf("[HTTP] POST... code: %d\n", httpsCode);

      // file found at server
      if (httpsCode == HTTP_CODE_OK) {
        const String& payload = https.getString();
        Serial.println("received payload:\n<<");
        Serial.println(payload);
        Serial.println(">>");
      }
      }else {
        Serial.printf("[HTTPS] GET... failed, error: %s\n", https.errorToString(httpsCode).c_str());
      }
      https.end();
    } 
}
