(function(){

	function init(){
		for( var button of document.querySelectorAll('.reset-default-button') ) {
			button.addEventListener( 'click', function(e){
				var button = this,
					input = button.closest('td').querySelector('.reset-default');
				if( ! input ) return;
				input.value = input.placeholder;
				e.preventDefault();
			}, false );
		}
	}

	document.addEventListener( 'DOMContentLoaded', init, false );

})();