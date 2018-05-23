                    var loadurl;
                    var edit;
                    function loader(){
                        if(edit===1) {
                               document.getElementById('city').style.display="block";
                      document.getElementById('county').style.display="block";   
                        }
                        else{
                            
                             document.getElementById('city').style.display="none";
                      document.getElementById('county').style.display="none";   
                        }
                     
                  
                      $.get(loadurl,{"id":0,"cdn":0},function(msg){
                            $("#province").empty();
                            $('#province').append("<option>--请选择--</option>");
                            for(var key in msg)
                              
                                $('#province').append("<option value="+msg[key].id+">"+msg[key].name+"</option>");
                            }    
                        );
            }
                    function getarea(cdn,iden){
                        var data;
                     
                        switch(iden){
                            case 'a':
                                switch(cdn){
                                       case '--请选择--':
                                   
                                        document.getElementById('city').style.display="none";
                                        document.getElementById('county').style.display="none";
                                        break;
                                    
                                    default:
                                         data= {'id':1,'cdn':cdn};
                                            $.get(loadurl,data,function(msg){ 
                                        $("#city").empty();
                                         $('#city').append("<option>--请选择--</option>");                           
                                        for(var key in msg)
                                     $('#city').append("<option value="+msg[key].id+">"+msg[key].name+"</option>");                         
                            });
                            document.getElementById('city').style.display="block";
                                        break; 
                                }break;    
                            case 'b':
                                switch(cdn){
                                    case '--请选择--':
                                        document.getElementById('county').style.display="none";
                                        break;
                                    default:
                                          data= {'id':2,'cdn':cdn};
                               $.get(loadurl,data,function(msg){ 
                            $("#county").empty();
                            $('#county').append("<option>--请选择--</option>");
                                for(var key in msg)
                                    $('#county').append("<option value="+msg[key].id+">"+msg[key].name+"</option>");
                            });
                         document.getElementById('county').style.display="block";
                                        break; 
                                }break;
                                
                        }
                    
                    }       