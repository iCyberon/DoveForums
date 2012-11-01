$(document).ready(function() {
    
    // Removed message boxes
    $("#errorBox").fadeOut(15000);
    $("#successBox").fadeOut(15000);  
    
    // Tooltip on all links     
    $('a[title]').qtip();

});