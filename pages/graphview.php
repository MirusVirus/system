<?php 

session_start();
$section = 'graph';

require $_SERVER['DOCUMENT_ROOT'] . '/system/documenthead.php';  // includes <body> tag as well as jquery, sorttable.js, datepicker.js

$graphsection = $_GET['section']; 
$graphid = $_GET['id']; 
/*
*/
?>



<svg id="graph" width="1400" height="600"></svg>
<script src="https://d3js.org/d3.v4.min.js"></script>
<script>


var svg = d3.select("svg"),
    width = +svg.attr("width"),
    height = +svg.attr("height");
    
    
var nodes_data =  [
    {"name": "Travis", "sex": "M"},
    {"name": "Rake", "sex": "M"},
    {"name": "Diana", "sex": "F"},
    {"name": "Rachel", "sex": "F"},
    {"name": "Shawn", "sex": "M"},
    {"name": "Emerald", "sex": "F"}
    ]

var links_data = [
	{"source": "Travis", "target": "Rake"},
    {"source": "Diana", "target": "Rake"},
    {"source": "Diana", "target": "Rachel"},
    {"source": "Rachel", "target": "Rake"},
    {"source": "Rachel", "target": "Shawn"},
    {"source": "Emerald", "target": "Rachel"}
]


//set up the simulation 
var simulation = d3.forceSimulation()
					//add nodes
					.nodes(nodes_data);
                    
                    
//add forces
//we're going to add a charge to each node, a centering force and a link force
var link_force =  d3.forceLink(links_data)
                        .id(function(d) { return d.name; });
simulation
    .force("charge_force", d3.forceManyBody())
    .force("center_force", d3.forceCenter(width / 2, height / 2))
    .force("links",link_force);

        
//add tick instructions: 
simulation.on("tick", tickActions );

//draw circles for the links 
var node = svg.append("g")
        .attr("class", "nodes")
        .selectAll("circle")
        .data(nodes_data)
        .enter()
        .append("circle")
        .attr("r", 10)
        .attr("fill", "lime");  


//draw lines for the links 
var link = svg.append("g")
      .attr("class", "links")
    .selectAll("line")
    .data(links_data)
    .enter().append("line")
      .attr("stroke-width", 2);        
                
      
var drag_handler = d3.drag()
	.on("start", drag_start)
	.on("drag", drag_drag)
	.on("end", drag_end);	
	
//same as using .call on the node variable as in https://bl.ocks.org/mbostock/4062045 
drag_handler(node)

//drag handler
function drag_start(node) {
 if (!d3.event.active) simulation.alphaTarget(0.3).restart();
   node.fx = node.x;
   node.fy = node.y;
}

function drag_drag(node) {
  node.fx = d3.event.x;
  node.fy = d3.event.y;
}

function drag_end(node) {
  if (!d3.event.active) simulation.alphaTarget(0);
  node.fx = null;
  node.fy = null;
}
      
function tickActions() {
    //update circle positions each tick of the simulation 
    node
        .attr("cx", function(d) { return d.x; })
        .attr("cy", function(d) { return d.y; });
        
    //update link positions 
    //simply tells one end of the line to follow one node around
    //and the other end of the line to follow the other node around
    link
        .attr("x1", function(d) { return d.source.x; })
        .attr("y1", function(d) { return d.source.y; })
        .attr("x2", function(d) { return d.target.x; })
        .attr("y2", function(d) { return d.target.y; });
} 


</script>