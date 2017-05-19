;(function($){

 $.fn.extend({  
      "fixedsSerializeArray": function () {  
          var $f = $(this);  
          var data = $(this).serializeArray();  
          var $chks = $(this).find(":checkbox:not(:checked)");//取得所有未选中的checkbox  

          if ($chks.length == 0) {  
              return data;  
          }  
          var nameArr = [];  
          var tempStr = "";
          $chks.each(function () {  
              var chkName = $(this).attr("name");   
              if ($.inArray(chkName, nameArr) == -1 && $f.find(":checkbox[name='" + chkName + "']:checked").length == 0) {  
                  nameArr.push(chkName);
                  tempStr = "{name: '"+chkName+"', value: ''}"
                  var newJson = eval("("+tempStr+")");
                  data.push(newJson);
              }  
          });
          return data;  
      }  
  });

})(jQuery)
