/* 
 * Copyright 2015 Version 1.0.0
 * Pour le Pass, projet gestion de log.
 * @author Huygens Adrien
 * contact adrien.huygens@gmail.com
 */


function masquer(){
   
    if (document.getElementById("form_dates_signe").value == "<"){
    document.getElementById("d1").style.visibility = "visible";
    document.getElementById("d2").style.visibility = "hidden";
    
    
    }
    else if (document.getElementById("form_dates_signe").value == "="){
    document.getElementById("d1").style.visibility = "visible";
    document.getElementById("d2").style.visibility = "hidden";
    
    
    }
    else if (document.getElementById("form_dates_signe").value =="between"){
    document.getElementById("d1").style.visibility = "visible";
    document.getElementById("d2").style.visibility = "visible";
    
    
    }
    else {
    document.getElementById("d1").style.visibility = "hidden";
    document.getElementById("d2").style.visibility = "hidden";
    }
    
}

function filtre(){
    
    document.getElementById("filtre").style.display = "";
    document.getElementById("btn").style.display = "none";
    
}
function filtre2(o){
    filtre();
       
    if((document.getElementById("form_dates_signe").value == "" && document.getElementById("form_hosts").value == "" && document.getElementById("form_groupes").value == "" )|| o ==1){
    document.getElementById("filtre").style.display = "none";
    document.getElementById("btn").style.display = "";
    }
}