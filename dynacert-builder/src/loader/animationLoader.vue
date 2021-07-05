<template>
    <div class="bodyDynacert">
        <div class="preloader-2">
        <span class="line line-1"></span>
        <span class="line line-2"></span>
        <span class="line line-3"></span>
        <span class="line line-4"></span>
        <span class="line line-5"></span>
        <span class="line line-6"></span>
        <span class="line line-7"></span>
        <span class="line line-8"></span>
        <span class="line line-9"></span>
        <div>Loading</div>
        </div>
    </div>
</template>


<style>
.bodyDynacert{
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
.preloader-2 {
  margin: 120px auto 0;
}


.preloader-1 .line {
  width: 1px;
  height: 12px;
  background: #fff;
  margin: 0 1px;
  display: inline-block;
  animation: opacity-1 1000ms infinite ease-in-out;
}

.preloader-2 .line {
  width: 1px;
  height: 12px;
  background: #fff;
  margin: 0 1px;
  display: inline-block;
  animation: opacity-2 1000ms infinite ease-in-out;
}

.preloader-1 .line-1, .preloader-2 .line-1 { animation-delay: 800ms; }
.preloader-1 .line-2, .preloader-2 .line-2 { animation-delay: 600ms; }
.preloader-1 .line-3, .preloader-2 .line-3 { animation-delay: 400ms; }
.preloader-1 .line-4, .preloader-2 .line-4 { animation-delay: 200ms; }
.preloader-1 .line-6, .preloader-2 .line-6 { animation-delay: 200ms; }
.preloader-1 .line-7, .preloader-2 .line-7 { animation-delay: 400ms; }
.preloader-1 .line-8, .preloader-2 .line-8 { animation-delay: 600ms; }
.preloader-1 .line-9, .preloader-2 .line-9 { animation-delay: 800ms; }

@keyframes opacity-1 { 
  0% { 
    opacity: 1;
  }
  50% { 
    opacity: 0;
  }
  100% { 
    opacity: 1;
  }  
}

@keyframes opacity-2 { 
  0% { 
    opacity: 1;
    height: 15px;
  }
  50% { 
    opacity: 0;
    height: 12px;
  }
  100% { 
    opacity: 1;
    height: 15px;
  }  
}


</style>