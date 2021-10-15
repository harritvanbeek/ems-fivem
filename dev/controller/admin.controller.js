"use strict";
boann.controller('AdminController', ['$scope', '$http', '$window', '$state', '$stateParams', function($scope, $http, $window, $state, $stateParams) {
    console.log($state.$current.url.pattern.split("/")[1] + " = case name");
    console.log($state.router.globals.$current.views.mainpage.controller + "Controller is Loaded");

    var URI         =   controler.view + "admin/index.php";
    var state       =   $state.$current.url.pattern.split("/")[1];

    switch(state){
        case "users" :
            $scope.createUser = function(data){
                if(data){
                    var VALUES  = [{data:data}];
                    $http.post(URI, VALUES, {params:{action:"createUser"}}).then(function(data){
                        if(data.status === 200){
                            switch(data.data.data){
                                case "success":
                                    swal(
                                        "Well done!!",
                                        data.data.dataContent, 
                                        "success"
                                    ).then(function(value){
                                        if(value === true){
                                            $('#createUser').modal("hide");
                                            getUsers();                                
                                        }
                                    });
                                break;

                                case "error":
                                    $scope.error = data.data.dataContent;
                                break;
                            }
                        }
                    });                    
                }else{
                    swal("Oeps!", "All fields are required", "error"); 
                }
            }

            $scope.editUser = function(data){
                if(data){                    
                    var VALUES  = [{data:data}];
                    $http.post(URI, VALUES, {params:{action:"updateUser"}}).then(function(data){
                        if(data.status === 200){
                            switch(data.data.data){
                                case "success" :
                                    swal(
                                        "Well done!!",
                                        data.data.dataContent, 
                                        "success"
                                    ).then(function(value){
                                        if(value === true){
                                            $window.location.reload();                               
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
            }

            $scope.edit     = function(data){
                $scope.user = data;
            }
            //83c44703-41ba-47ae-bffb-7505193402d3
            $scope.trash    = function(data){
                if(data){
                    swal({
                        title: "Delete user!!",
                        text: "You are sure to delete "+ data.MeFname+" "+data.MeLname,
                        closeOnClickOutside: false,
                        closeOnEsc: false,
                        icon: "info",
                    }).then(function(respons){
                        if(respons === true){
                            var VALUES  = [{data:data}];
                            $http.post(URI, VALUES, {params:{action:"trashUser"}}).then(function(data){
                                console.log(data.data);
                                switch(data.data.data){
                                    case "success" :
                                        swal(
                                            "Well done!!",
                                            data.data.dataContent, 
                                            "success"
                                        ).then(function(value){
                                            if(value === true){
                                                getUsers();                              
                                            }
                                        });
                                    break;

                                    case "error" :
                                        swal("Oeps!", data.data.dataContent, "error");                                    
                                    break;
                                }
                            });
                        };
                    }); 
                }
            }

            getUsers();            
        break;
    }

    chekLogin();

    function getUsers(){
        $http.get(URI, {params:{action:"getUsers"}}).then(function(data){
            if(data.status === 200){
                $scope.users = data.data; 
            }
        });
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