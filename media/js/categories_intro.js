window.addEventListener("load", function(){     
    let $ = jQuery;
    $('.category-item img, .category-item div').click(function(e) {        
        let url = $(e.target).parents('.category-item:first').find('a.btn:last').attr('href')
        if (url!==undefined) window.location = url;
    })

    $('.article img, .article h3').click(function(e) {        
        let url = $(e.target).parents('.article:first').find('a.btn:last').attr('href')
        if (url!==undefined) window.location = url;
    })

    
        
})