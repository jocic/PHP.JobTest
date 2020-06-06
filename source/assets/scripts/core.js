/***********************************************************\
|* Author: Djordje Jocic                                   *|
|* Website: http://www.djordjejocic.com/                   *|
|* Email Address: office@djordjejocic.com                  *|
|* ------------------------------------------------------- *|
|* Filename: application.js                                *|
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
    
});
