angular.module('widgets', [])
    .directive('searchresults', searchResults)
    .directive('loadInitialFeed', loadInitialFeed)
    .directive('fetchfeeds', fetchFeeds)

function searchResults() {
    var directive = {
        restrict: 'EAC',
        templateUrl: '/searchresults.html',
        scope: {
            data: '='
        },
        controller : SearchResultsController, 
    };
    return directive; 
}

SearchResultsController.$inject = ['$scope'];

function SearchResultsController($scope) {
    var vm = this; 
    vm.age = "32";
}

function loadInitialFeed() {
    var directive = {
        restrict: 'EAC',
        templateUrl: '/feeds.html',
        controller : InitialFeedController, 
    };
    return directive; 
    
}

InitialFeedController = ["$scope", 'feeds'];

function InitialFeedController($scope, feeds)  {
    
}

function fetchFeeds() { 
    var directive = {
        restrict: 'EAC',
        template: '<button type="button" class="btn btn-primary btn-lg btn-block" id="load-more">Load More</button>',
        scope: {
            next: '=', 
            prev: '=', 
            page_id: '=', 
        },
        controller : FetchMoreResultsController, 
    };
    return directive; 
}

FetchMoreResultsController.$inject = ['$scope', 'feeds'];

function FetchMoreResultsController($scope, feeds) { 
    var vm = this; 
    feeds.fetch('212923738872880', 1).then(function(feed_data) {
        console.log(feed_data);
    })
}
