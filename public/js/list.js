// jQuery UI autocomplete extension - suggest labels may contain HTML tags
// github.com/scottgonzalez/jquery-ui-extensions/blob/master/src/autocomplete/jquery.ui.autocomplete.html.js
(function($){var proto=$.ui.autocomplete.prototype,initSource=proto._initSource;function filter(array,term){var matcher=new RegExp($.ui.autocomplete.escapeRegex(term),"i");return $.grep(array,function(value){return matcher.test($("<div>").html(value.label||value.value||value).text());});}$.extend(proto,{_initSource:function(){if(this.options.html&&$.isArray(this.options.source)){this.source=function(request,response){response(filter(this.options.source,request.term));};}else{initSource.call(this);}},_renderItem:function(ul,item){return $("<li></li>").data("item.autocomplete",item).append($("<a></a>")[this.options.html?"html":"text"](item.label)).appendTo(ul);}});})(jQuery);

//   var cache = {};



function googleSuggest(request, response) {
    var term = request.term;
    // if (term in cache) { response(cache[term]); return; }
    /*$.ajax({
        url: 'http://query.yahooapis.com/v1/public/yql',
        dataType: 'JSONP',
        data: { format: 'json', q: 'select * from xml where url="http://google.com/complete/search?output=toolbar&q='+term+'"' },
        success: function(data) {
            var suggestions = [];
            try { var results = data.query.results.toplevel.CompleteSuggestion; } catch(e) { var results = []; }
            $.each(results, function() {
                try {
                    var s = this.suggestion.data.toLowerCase();
                    suggestions.push({label: s.replace(term, '<b>'+term+'</b>'), value: s});
                } catch(e){}
            });
            cache[term] = suggestions;
            response(suggestions);
        }
    });*/
}

$(function() {
    $('#hero-demo').tagEditor({
        placeholder: 'Enter tags ...',
        autocomplete: { source: googleSuggest, minLength: 3, delay: 250, html: true, position: { collision: 'flip' } }
    });

    /*   $('#demo1').tagEditor({ initialTags: ['Hello', 'World', 'Example', 'Tags'], delimiter: ', ', placeholder: 'Enter tags ...' }).css('display', 'block').attr('readonly', true);

       $('#demo2').tagEditor({
           autocomplete: { delay: 0, position: { collision: 'flip' }, source: ['ActionScript', 'AppleScript', 'Asp', 'BASIC', 'C', 'C++', 'CSS', 'Clojure', 'COBOL', 'ColdFusion', 'Erlang', 'Fortran', 'Groovy', 'Haskell', 'HTML', 'Java', 'JavaScript', 'Lisp', 'Perl', 'PHP', 'Python', 'Ruby', 'Scala', 'Scheme'] },
           forceLowercase: false,
           placeholder: 'Programming languages ...'
       });

       $('#demo3').tagEditor({ initialTags: ['Hello', 'World'], placeholder: 'Enter tags ...' });*/
    $('#remove_all_tags').click(function() {
      //  var tags = $('#demo3').tagEditor('getTags')[0].tags;
      //  for (i=0;i<tags.length;i++){ $('#demo3').tagEditor('removeTag', tags[i]); }
    });

    /* $('#demo4').tagEditor({
         initialTags: ['Hello', 'World'],
         placeholder: 'Enter tags ...',
         onChange: function(field, editor, tags) { $('#response').prepend('Tags changed to: <i>'+(tags.length ? tags.join(', ') : '----')+'</i><hr>'); },
         beforeTagSave: function(field, editor, tags, tag, val) { $('#response').prepend('Tag <i>'+val+'</i> saved'+(tag ? ' over <i>'+tag+'</i>' : '')+'.<hr>'); },
         beforeTagDelete: function(field, editor, tags, val) {
             var q = confirm('Remove tag "'+val+'"?');
             if (q) $('#response').prepend('Tag <i>'+val+'</i> deleted.<hr>');
             else $('#response').prepend('Removal of <i>'+val+'</i> discarded.<hr>');
             return q;
         }
     });*/

    //  $('#demo5').tagEditor({ clickDelete: true, initialTags: ['custom style', 'dark tags', 'delete on click', 'no delete icon', 'hello', 'world'], placeholder: 'Enter tags ...' });

    function tag_classes(field, editor, tags) {
        $('li', editor).each(function(){
            var li = $(this);
            if (li.find('.tag-editor-tag').html() == 'red') li.addClass('red-tag');
            else if (li.find('.tag-editor-tag').html() == 'green') li.addClass('green-tag')
            else li.removeClass('red-tag green-tag');
        });
    }
    //  $('#demo6').tagEditor({ initialTags: ['custom', 'class', 'red', 'green', 'demo'], onChange: tag_classes });
    //  tag_classes(null, $('#demo6').tagEditor('getTags')[0].editor); // or editor == $('#demo6').next()
});