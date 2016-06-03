// Botones sociales contador
var share = {
        jsonRequest : function(url) {
            try {
                scr = document.createElement('script');                        
                scr.setAttribute('src',url);            
                document.getElementsByTagName("HEAD")[0].appendChild(scr);
            } catch(e) {
            }
        },
        twitterCountCallback : function(response){
            try{
                if(response != null && response['count'] != null){
                    $( ".twcnt" ).html( response['count']);
                }
            }catch (e) {
            }
        },
        fbCountCallback : function(response) {
            try{
                if(response != null){
                    var parser=new DOMParser();
                    var xmlDoc=parser.parseFromString(response,"text/xml");
                    var node = xmlDoc.getElementsByTagName('total_count')[0];                    
                    if(node != null){
                        count = node.childNodes[0].nodeValue;
                        if(count != null){
                           $( ".fbcnt" ).html( count );
                        }                
                    }
                    
                    node = xmlDoc.getElementsByTagName('commentsbox_count')[0];                    
                    if(node != null){
                        count = node.childNodes[0].nodeValue;
                        if(count != null){
                            $( "#fbcommentcount2" ).html( count+'&nbsp;' );   
                        }                
                    }              
                    
                }
            }catch (e) {
            }
        }
};

var scraper = {
        removeFlashEmbedTag : function(){
            try{
                var nodes = document.getElementsByTagName("object");
                var size = nodes.length;
                for(i=0; i<size; i++){
                    var object = nodes[i];
                    var attributes = object.attributes;
                    if(attributes == null || attributes.getNamedItem("id")==null){
                        object.innerHTML='';
                        object.style.height='0px !important';
                        object.style.display='none !important';
                    }
                }
            }catch(e){
                console.log(e);
            }
        }
};

