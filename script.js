$(document).ready(function(){
    $('.remove').click(function(){
        const id = $(this).attr('id');
        
        $.post("controllers/remove.php",
              {
                  id: id
              },
              (data)  => {
                 if(data){
                     $(this).parent().hide(600);
                 }
              }
        );
    });
});