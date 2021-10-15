"use strict";
boann.controller('DashboardController', ['$scope', '$http', '$window', '$state', '$stateParams', function($scope, $http, $window, $state, $stateParams) {
    console.log($state.$current.url.pattern.split("/")[1] + " = case name");
    console.log($state.router.globals.$current.views.mainpage.controller + "Controller is Loaded");

    var URI         =   controler.view + "dashboard/index.php";
    var state       =   $state.$current.url.pattern.split("/")[1];

    switch(state){
        case "update-document" :            
            $http.get(URI, {params:{action:"getUpdateDocument", uuid:$stateParams.uuid}}).then(function(data){
                if(data.status === 200){
                    $scope.user = data.data;                    
                }
            });

            $scope.setDocument = function(data){
                var VALUES  = [{data:data}];
                $http.post(URI, VALUES, {params:{action:"setDocument"}}).then(function(data){
                    if(data.status === 200){
                        switch(data.data.data){
                            case "success" :
                                swal(
                                    "Well done!!",
                                    data.data.dataContent, 
                                    "success"
                                ).then(function(value){
                                    if(value === true){
                                        $state.go("dashboard");                                
                                    }
                                });
                            break;
                            
                            case "error" :
                                swal("Oeps!", data.data.dataContent, "error");
                            break
                        }
                    }
                });
            }            
        break;

        case "read-document" :
            $http.get(URI, {params:{action:"getReadDocument", uuid:$stateParams.uuid}}).then(function(data){
                if(data.status === 200){
                    console.log(data.data);
                    $scope.user = data.data;                    
                }
            });
        break;

        case "all-documents" :
            $http.get(URI, {params:{action:"getAllDocument", uuid:$stateParams.uuid}}).then(function(data){
                if(data.status === 200){
                    $scope.items = data.data;                    
                }
            });
        break;

        case "new-documents" :
            getClientInfo($stateParams.uuid);
            
            $scope.setDocument = function(data){
                var VALUES  = [{data:data}];
                $http.post(URI, VALUES, {params:{action:"setDocument"}}).then(function(data){
                    if(data.status === 200){
                        switch(data.data.data){
                            case "success" :
                                swal(
                                    "Well done!!",
                                    data.data.dataContent, 
                                    "success"
                                ).then(function(value){
                                    if(value === true){
                                        $state.go("dashboard");                                
                                    }
                                });
                            break;
                            
                            case "error" :
                                swal("Oeps!", data.data.dataContent, "error");
                            break
                        }
                    }
                });
            }

            /*$scope.tinymceOptions = {
                  height: 400,
                  menubar: true,
                  plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table paste code help wordcount'
                  ],
                  toolbar: 'undo redo | formatselect | ' +
                  'bold italic backcolor | alignleft aligncenter ' +
                  'alignright alignjustify | bullist numlist outdent indent | ' +
                  'removeformat',
                  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
            };*/

            
        break;

        case "create-profile" :
            $scope.save = function(item){
                var VALUES  = [{data:item}];
                $http.post(URI, VALUES, {params:{action:"createProfile"}}).then(function(data){
                    if(data.status === 200){                    
                        console.log(data.data);
                        switch(data.data.data){
                            case "success" :
                                swal(
                                    "Well done!!",
                                    data.data.dataContent, 
                                    "success"
                                ).then(function(value){
                                    if(value === true){
                                        $state.go("dashboard");                                
                                    }
                                });
                            break;
                            
                            case "error" :
                                swal("Oeps!", data.data.dataContent, "error");
                            break
                        }
                    }
                });
            }
        break;

        case "dashboard" :
            $scope.findClients  =   function(data){
                if(data){
                    var VALUES  = [{data:data}];
                    $http.post(URI, VALUES, {params:{action:"findClient"}}).then(function(data){
                        if(data.status === 200){
                            console.log(data.data);
                            $scope.clients = data.data;                    
                        }
                    });
                }
            }
        break;
    }


    chekLogin();

    function getClientInfo(data){
        if(data){
            var VALUES  = [{data:data}];
            $http.post(URI, VALUES, {params:{action:"getClientInfo"}}).then(function(data){
                if(data.status === 200){
                    $scope.user = data.data;                    
                }
            });
        }
    }

    function chekLogin(){
        $http.post(URI).then(function(data){
            if(data.status === 200){ 
                if(data.data.data == 'false'){
                    $state.go("login");                    
                };
            } 
        });
    }
    
}]);