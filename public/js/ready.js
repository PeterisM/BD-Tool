function decorateTableJq( obj ){
    var tmp = 0;

    $j('tr:first', obj).addClass('first');
    $j('tr:last', obj).addClass('last');

    $j('tr', obj).each( function(){
        if ( tmp )
            $j(this).addClass('even');
        else
            $j(this).addClass('odd');

        tmp = 1-tmp;
    });
}  
   
$j(document).ready(function() {

    //Pieliek tabulai klases
	$j('.data-table').each( function(){
        decorateTableJq( $j(this) );
    });
  
    $j('#tabs > div').hide();
    $j('#tabs > div:first').show();
    $j('#tabs ul li:first').addClass('active');
    $j('#tabs > ul li a').click(function(){
        $j('#tabs > ul li').removeClass('active');
        $j(this).parent().addClass('active');
        var currentTab = $j(this).attr('href');
        $j('#tabs > div').hide();
        $j(currentTab).show();
        return false;
    });
});

