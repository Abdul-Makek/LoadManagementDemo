$(document).ready(function(){
    (function(){
        if(typeof(Storage) !== "undefined"){
            if(sessionStorage.toastMsg != ''){
                showToastMessage(sessionStorage.toastType, sessionStorage.toastMsg);
                    sessionStorage.toastType = '';
                    sessionStorage.toastMsg = '';
            }
        }
    })();
})

function showToastMessage(type, msg){
    if($('#toastContainer').find('div').length > 0){
        $('#toastContainer').find('div').remove();
    }
    
    // Prepare toast DOM
    let toastClass = '';
    
    if(type == "success"){
        toastClass = 'toast-success';
    }else if(type=='failed'){
        toastClass = 'toast-danger';
    }else{
        toastClass = 'toast-alert';
    }
    
    let toastDom = '<div class="toast '+ toastClass +'" aria-live="assertive" role="alert" aria-atomic="true">';
    toastDom += '<div class="toast-body">' + msg + '</div></div>';
    
    $('#toastContainer').prepend(toastDom);   // inject toast DOM
 
    $('.toast').toast('show');
}