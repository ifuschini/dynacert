<template>
    <div class="bodyUrc">
  		<div class="loaderUrc">
			<span>D</span>
			<span>Y</span>
			<span>N</span>
			<span>A</span>
			<span>C</span>
			<span>E</span>
			<span>R</span>
			<span>T</span>
		</div>
    </div>
</template>

<script>
export default {

}
</script>

<style>
.bodyUrc{
    position: absolute;
    top: 0px;
    left:0px;
	background-color: rgb(182, 173, 173);
	padding: 0;
	margin: 0;
	height: 100vh;
	width:100vw;
	display:flex;
	align-items: center;
	justify-content: center;
    opacity: 0.9;
}

.loaderUrc{   
	-webkit-perspective:700px;
	perspective: 700px;
}

.loaderUrc>span{
	font-size: 150px;
    color:rgb(29, 14, 236);
	font-family: "franklin gothic medium",sans-serif;
	display: inline-block;
	animation:flip 2.0s infinite linear;
	transform-origin:0 70%;
	transform-style:preserve-3d;
	-webkit-transform-style:preserve-3d;
}

@keyframes flip{
	35%{
		transform: rotateX(360deg);
	}
	100%{
		transform: rotatex(360deg);
	}
}


.loaderUrc>span:nth-child(even){
	color:rgb(133, 5, 5);

}

.loaderUrc>span:nth-child(2){
	animation-delay: 0.3s;
}

.loaderUrc>span:nth-child(4){
	animation-delay: 0.9s;
}
.loaderUrc>span:nth-child(5){
	animation-delay: 1.2s;
}
.loaderUrc>span:nth-child(6){
	animation-delay: 1.5s;
}
.loaderUrc>span:nth-child(7){
	animation-delay: 1.8s;
}
.loaderUrc>span:nth-child(8){
	animation-delay: 2.1s;
}
</style>