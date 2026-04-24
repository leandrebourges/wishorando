 <?php
//open gpx file
$gpx = simplexml_load_file("test.gpx");
foreach ($gpx->trk as $trk) {
    foreach($trk->trkseg as $seg){
        foreach($seg->trkpt as $pt){
            echo "<p> lat- ";
            echo $pt["lat"];
            echo "   lon-";
            echo $pt["lon"];
            echo "   ele-";
            echo $pt->ele;
            echo"<p>";
            
        }}}
unset($gpx);
?>