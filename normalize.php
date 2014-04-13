<?php
 $document = new DomDocument('1.0', 'UTF-8');
 $document->load("/root/1.svg");

 $minX=0;
 $maxX=0;
 $minY=0;
 $maxY=0;

 // start normalizing

 //
 function normalize_node($root_node)
 {
 	global  $minX,$minY,$maxX,$maxY;
 // walk thru all nodes
$restart=1;
while($restart==1)
{
	$restart=0;

foreach ($root_node->childNodes as $node) {
	if(strcmp($node->nodeName,'#text')!=0)
    		{
//   echo "parse ".$node->nodeName."\n"; // body
    $remove=0;
    if(strcmp($node->nodeName,"defs")==0)
    {
    	// remove ClipPathGroup and EmbeddedBulletChars
    	if(strcmp($node->getAttribute("class"),"ClipPathGroup")==0)
    	{
    		$remove=1;
    	}

		if(strcmp($node->getAttribute("class"),"EmbeddedBulletChars")==0)
    	{
    		$remove=1;
    	}	
    }

	if(strcmp($node->nodeName,"g")==0)
    {
    	// remove ClipPathGroup and EmbeddedBulletChars
    	if(strcmp($node->getAttribute("class"),"com.sun.star.drawing.TextShape")==0)
    	{
    		$remove=1;
    	}	
    	//clip-path
    	if($node->hasAttribute("clip-path"))
    	{
    		$node->removeAttribute("clip-path");
    	}
    }

    if(strcmp($node->nodeName,"path")==0)
    {
    	// ok collect min/max/X/Y
    	foreach (explode(" ",$node->getAttribute("d")) as $key => $value) {
    		$c=explode(",",$value);
    		if(count($c)>1)
    		{
    			// $c now contains X/Y
    			$x=intval($c[0]);$y=intval($c[1]);
    			if($x>$maxX) $maxX=$x;
    			if($y>$maxY) $maxY=$y;

    			if($x<$minX) $minX=$x;
    			if($y<$minY) $minY=$y;
    		}
    	}
    }
    // removing, if nessesary

    if($remove==1)
    	{
//    		echo $node->nodeName." ".$node->getAttribute("class")." removed\n";
    		$root_node->removeChild($node);
    		$restart=1;
    	}
    	else
    	{
    		
    			normalize_node($node);  // and recurce to child
    	
    	}
	}}
}
}

normalize_node($document->documentElement);

// ok, now change root SVG node

$document->documentElement->removeAttribute("clip-path");
// <min-x>, <min-y>, <width> and <height>
$document->documentElement->setAttribute("viewBox",intval($minX)." ".intval($minY)." ".intval(abs($minX)+abs($maxX))." ".intval(abs($minY)+abs($maxY)));
$document->documentElement->setAttribute("width",intval((abs($minX)+abs($maxX))/100)."mm");
$document->documentElement->setAttribute("height",intval((abs($minY)+abs($maxY))/100)."mm");
//echo $minX." ".$minY." ".$maxX." ".$maxY."\n";

$document->save('/root/1.svg');
?>
