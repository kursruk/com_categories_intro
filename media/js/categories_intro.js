window.addEventListener("load", function(){     
    let $ = jQuery;
    $('.category-item img').click(function(e) {
        
        let url = $(e.target).next().find('a.btn:last').attr('href')
        if (url!==undefined) window.location = url;
    })
})