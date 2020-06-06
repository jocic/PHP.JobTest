/***********************************************************\
|* Author: Djordje Jocic                                   *|
|* Website: http://www.djordjejocic.com/                   *|
|* Email Address: office@djordjejocic.com                  *|
|* ------------------------------------------------------- *|
|* Filename: core.js                                       *|
|* ------------------------------------------------------- *|
|* Copyright (C) 2019                                      *|
\***********************************************************/

$(document).ready(function() {
    
    $(".time").datetimepicker({
        format : "LT"
    });
    
    $(".date").datetimepicker({
        format : "L"
    });
    
    $("form").submit(function(e) {
        
        $.post("process.php", $(this).serialize())
            .done(function() {
                $(".toast.success").toast({ "delay" : 2000 }).toast("show");
            })
            .fail(function() {
                $(".toast.error").toast({ "delay" : 2000 }).toast("show");
            })
        
        e.preventDefault();
        
    });
    
});
