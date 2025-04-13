class Message{

    constructor() {
        $(".info-message .topic .close_message").on("click", function (){
            Message.close()
        });
    }
    showError(title, text, time=5000){
        this.setTitle(title);
        this.setDescription(text);
        this.setClass("error")
        Message.open();
        setTimeout(Message.close, time);
    }
    static close(){
        $(".info-message").fadeOut();
    }

    static open(){
        $(".info-message").fadeIn();
    }
    setTitle(title){
        $(".info-message .topic h2").html(title);
    }
    setDescription(text){
        $(".info-message p").html(text);
    }
    setClass(name){
        $("#infoMessage").removeClass()
        $("#infoMessage").addClass("info-message");
        $("#infoMessage").addClass(name);
    }
}

$(document).ready(function (){
    $(".info-message").hide();
});