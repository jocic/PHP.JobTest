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
    
});
