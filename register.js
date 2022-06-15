const element = document.querySelector('.message_container');

if(window.iscounter == 'activated'){
    
    element.classList.toggle("invisible");
    setInterval(get_message, 2000);
    send_enroll_id_to_adafruitio(window.last_userid);
    activate_adafruit_register_feed(); 

}


// message box
const alertbox = document.querySelector('.message_body');

function get_message() {
    const xhr = new XMLHttpRequest();
    xhr.onload = function() {
        show_message(this.response);
        console.log(this.response);
    }
    xhr.open('POST', './api/mcu_messages.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('get_message=true');
}


function show_message(message){
    if(message.includes("Remove")){
         alertbox.classList.add("red");  
    }else if(message.includes("Success")){
        alertbox.classList.add("green");
        create_new_user();
        console.log('password: '+password );
        console.log("new user created"+username);
        location.replace("./login.php");
    }
    console.log("message show");
    alertbox.innerHTML = message;
}
function create_new_user() {
    const xhr = new XMLHttpRequest();
    xhr.onload = function() { 
        console.log("new user created"+username);
    }
    xhr.open('POST', './api/userInsert.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('username='+username+'&password='+password);
}


function activate_adafruit_register_feed() {
    const xhr = new XMLHttpRequest();
    xhr.onload = function() {
        console.log('adafruit register activated');
    }
    xhr.open('POST', 'https://io.adafruit.com/api/v2/ganesh333/feeds/register/data?X-AIO-Key=aio_Kkui38HSRAHPSyCZ167wn0IgSQfA');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send("value=ON");  
}


function send_enroll_id_to_adafruitio(id) {
    console.log('enroll to adafruit id');
    id++;
    console.log(id);
    const xhr = new XMLHttpRequest();
    xhr.onload = function() {
        console.log(id);
    }
    xhr.open('POST', 'https://io.adafruit.com/api/v2/ganesh333/feeds/enrollid/data?X-AIO-Key=aio_Kkui38HSRAHPSyCZ167wn0IgSQfA');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send("value="+id);  
}



