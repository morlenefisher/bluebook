
jQuery(document).ready(function($) {
  
  $(".ctamodal").click(function(e) {
    e.preventDefault();
    linkid = $(this).attr('href');

    $(".ctamodal ~ div")
    .load(linkid)
    .dialog({
    
      title: $(this).attr('title'),
      width: 500,
      height: 300
    });
    //return false;
  }); 
  
});