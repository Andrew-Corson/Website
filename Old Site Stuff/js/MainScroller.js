//This basically is just an object that cycles through the number IDs of the
//slider-view divs
function View() {
	this.num = 1;
	this.max = $(".slider-view").length;
}

View.prototype.setNum = function(n) {
	this.num = n;
};

View.prototype.getPrev = function() {
	if(this.num == 1)
		return this.max;
	else
		return this.num + 1;
}

View.prototype.getNext = function() {
	if(this.num == this.max)
		return 1;
	else
		return this.num + 1;
};

View.prototype.next = function() {
	if(this.num == this.max) 
		this.num = 1;
	else
		this.num++;
};
//////////////////////////////////////////////////////////////////////////
var view = new View();
var speed = "slow";
var sideHeight = 650.0 / (view.max - 1);
$(".side-view").height(sideHeight);

document.getElementById("v1").style.marginLeft = "0px";

for(var i = 2; i <= view.max; i++) {
	document.getElementById("v" + i).style.marginLeft = "900px";
}

for(var s = view.max; s >=1; s--) {
	document.getElementById("s" + s).style.marginTop = 
	((view.max - s) * sideHeight) + "px";
}

setInterval(scroll, 5000);

function scroll() {
	//a speed animation take 600 miliseconds (fast would be 200)
	$("#v" + view.num).animate({marginLeft: "-900px"}, speed, 
	function() {
		document.getElementById("v" + view.num).style.marginLeft = "900px";
		view.next();
	});
	$("#v" + view.getNext()).animate({marginLeft: "0px"}, speed);
	
	var box;
	var view2 = new View();
	view2.setNum(view.num);
	
	document.getElementById("s" + view.num).style.marginTop = 
	(sideHeight * -1) + "px";

	for(var i = 1; i <= view.max; i++) {
		$("#s" + view2.getNext()).animate({marginTop: ((view.max - i) * sideHeight) + "px"}, speed);
		view2.next();
	}
}