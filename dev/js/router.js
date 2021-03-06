boann.config(['$stateProvider', '$urlMatcherFactoryProvider', '$urlRouterProvider', '$locationProvider', 
    function($stateProvider, $urlMatcherFactoryProvider, $urlRouterProvider, $locationProvider){
        $urlRouterProvider.otherwise("/");
        $urlMatcherFactoryProvider.caseInsensitive(true);
        $stateProvider
            .state({
                name:"login",
                url: "/",
                views : {
                    "nav" : {
                        templateUrl : "./html/navigation.html?v="+controler.version,                        
                    },
                    
                    "mainpage" : {
                        templateUrl : "./html/user/login.html?v="+controler.version,                        
                        controller  : "LoginController",
                    },
                }
            })
            .state({
                name:"dashboard",
                url: "/dashboard/",
                views : {
                    "nav" : {
                        templateUrl : "./html/nav.html?v="+controler.version,                        
                        controller  : "NavigationController",
                    },

                    "mainpage" : {
                        templateUrl : "./html/user/dashboard.html?v="+controler.version,                        
                        controller  : "DashboardController",
                    },
                }
            })
            .state({
                name:"clients",
                url: "/clients/",
                views : {
                    "nav" : {
                        templateUrl : "./html/nav.html?v="+controler.version,                        
                        controller  : "NavigationController",
                    },

                    "mainpage" : {
                        templateUrl : "./html/user/clients.html?v="+controler.version,                        
                        controller  : "DashboardController",
                    },
                }
            })
            .state({
                name:"createProfile",
                url: "/create-profile/",
                views : {
                    "nav" : {
                        templateUrl : "./html/nav.html?v="+controler.version, 
                        controller  : "NavigationController",                       
                    },

                    "mainpage" : {
                        templateUrl : "./html/user/create-profile.html?v="+controler.version,                        
                        controller  : "DashboardController",
                    },
                }
            })
            .state({
                name:"editProfile",
                url: "/edit-profile/:uuid/",
                views : {
                    "nav" : {
                        templateUrl : "./html/nav.html?v="+controler.version, 
                        controller  : "NavigationController",                       
                    },

                    "mainpage" : {
                        templateUrl : "./html/user/edit-profile.html?v="+controler.version,                        
                        controller  : "DashboardController",
                    },
                }
            })
            .state({
                name:"allDocuments",
                url: "/all-documents/:uuid/",
                views : {
                    "nav" : {
                        templateUrl : "./html/nav.html?v="+controler.version,
                        controller  : "NavigationController",                        
                    },

                    "mainpage" : {
                        templateUrl : "./html/user/all-documents.html?v="+controler.version,                        
                        controller  : "DashboardController",
                    },
                }
            })
            .state({
                name:"newDocuments",
                url: "/new-documents/:uuid/",
                views : {
                    "nav" : {
                        templateUrl : "./html/nav.html?v="+controler.version,
                        controller  : "NavigationController",                        
                    },

                    "mainpage" : {
                        templateUrl : "./html/user/new-documents.html?v="+controler.version,                        
                        controller  : "DashboardController",
                    },
                }
            })
            .state({
                name:"readDocument",
                url: "/read-document/:uuid/",
                views : {
                    "nav" : {
                        templateUrl : "./html/nav.html?v="+controler.version,  
                        controller  : "NavigationController",                      
                    },

                    "mainpage" : {
                        templateUrl : "./html/user/read-document.html?v="+controler.version,                        
                        controller  : "DashboardController",
                    },
                }
            })
            .state({
                name:"updateDocument",
                url: "/update-document/:uuid/",
                views : {
                    "nav" : {
                        templateUrl : "./html/nav.html?v="+controler.version,  
                        controller  : "NavigationController",                      
                    },

                    "mainpage" : {
                        templateUrl : "./html/user/new-documents.html?v="+controler.version,                        
                        controller: "DashboardController",
                    },
                }
            })
            .state({
                name:"updateOwnerDocument",
                url: "/update-owner-document/:uuid/",
                views : {
                    "nav" : {
                        templateUrl : "./html/nav.html?v="+controler.version,  
                        controller  : "NavigationController",                      
                    },

                    "mainpage" : {
                        templateUrl : "./html/user/update-documents.html?v="+controler.version,                        
                        controller: "DashboardController",
                    },
                }
            });
}]);