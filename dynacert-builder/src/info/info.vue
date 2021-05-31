<template>
    <div class="bodyDyna">
<div>
  <b-card
    title="Info"
    img-src="images/logo.png"
    img-alt="Image"
    img-top
    tag="article"
    style="max-width: 20rem;"
    class="mb-2"
  >
    <b-card-text>
      In this moment dyna-cert builder is not optimize for mobile, but only for desktop
    </b-card-text>

    <b-button href="https://www.dyna-cert.org" variant="primary">Return to home</b-button>
  </b-card>
</div>
    </div>
</template>


<style>
.bodyDyna{
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
    opacity: 1.0;
    visibility: hidden;
}
@media only screen and (max-width: 600px) {
  .bodyDyna {
    visibility:visible;
  }
}
</style>