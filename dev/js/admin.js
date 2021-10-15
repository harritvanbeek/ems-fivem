boann.config(['$stateProvider', '$urlMatcherFactoryProvider', '$urlRouterProvider', '$locationProvider', 
    function($stateProvider, $urlMatcherFactoryProvider, $urlRouterProvider, $locationProvider){
        $stateProvider
            .state({
                name:"users",
                url: "/users/",
                views : {
                    "nav" : {
                        templateUrl : "./html/nav.html?v="+controler.version, 
                        controller  : "NavigationController",                       
                    },
                    
                    "mainpage" : {
                        templateUrl : "./html/admin/users.html?v="+controler.version,                        
                        controller  : "AdminController",
                    },
                }
            })
}]);