<!--

function got(i) {

	window.location = "post.php?page="+(i);
}

function homel() {
	window.location = "index.php";
}

function FitToContent(id, maxHeight)
{
   var text = id && id.style ? id : document.getElementById(id);
   if ( !text )
      return;

   var adjustedHeight = text.clientHeight;
   if ( !maxHeight || maxHeight > adjustedHeight )
   {
      adjustedHeight = Math.max(text.scrollHeight, adjustedHeight);
      if ( maxHeight )
         adjustedHeight = Math.min(maxHeight, adjustedHeight);
      if ( adjustedHeight > text.clientHeight )
         text.style.height = adjustedHeight + "px";
   }
}

window.onload = function() {
    document.getElementById("ta").onkeyup = function() {
      FitToContent( this, 5000 )
    };
}

-->
