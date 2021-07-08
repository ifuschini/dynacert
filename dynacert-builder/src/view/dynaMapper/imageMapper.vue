<template>
  <v-stage ref="stage" :config="stageSize">
         <v-layer ref="layer"         
                            @mousemove="handleMouseMove"
                            @mouseout="handleMouseOut"
                            @mouseDown="handleMouseDown"
                            @mouseUp="handleMouseUp"
                            @dragstart="handleDragStart"
                            @dragend="handleDragEnd"
    >
      <v-image :config="{
            image: image
          }"/>
      <v-text ref="text" :config="{
                                x: 10,
                                y: 10,
                                fontFamily: 'Calibri',
                                fontSize: 24,
                                text: text,
                                fill: 'green'
      }"/>



        <v-rect v-for="item in rectangles" :key="item.id" :config="item" @transformend="handleTransformEnd"/>
            <v-transformer ref="transformer" />

    </v-layer>
  </v-stage>
</template>

<script>
const width = 200;
const height = 200;
const maxWidth=580;
const defaultColor='#000000'
const defaultStroke= [5, 5]

export default {
  props: [
      "imageToLoad"
  ],
  data() {
    return {
      stageSize: {
        width: width,
        height: height
      },
      image: null,
      ratio: 1,
      rectangles:  [],
      text: 'start',
      configObj: null,
      index: null,
      indexSelected: -1,
    };
  },
  created() {
    this.setImage()
  },
  watch: {
      imageToLoad() {
            if (this.imageToLoad!=null) {
              this.setImage()
            }

      }
  }, 
  methods: {
        setImage() {
                console.log('imageToLoad')
                const image = new window.Image();
                image.src = this.imageToLoad;
                this.image=image
                this.ratio=maxWidth/image.width
                this.stageSize={
                    width: maxWidth,
                    height: this.ratio*image.height
                }
                this.image.width=maxWidth
                this.image.height=this.ratio*image.height

        },
        clearSelection() {
          this.rectangles=[]
        },
        deleteSelection() {
          if (this.indexSelected != -1) {
            console.log("----->" + this.indexSelected)
            this.rectangles.splice(this.indexSelected,1)
          }
        },
        getSelection() {
          console.log('--------getSelections-----------')
          console.log(this.rectangles)
          this.$emit('getSelections',this.rectangles)
          console.log('------------------------------------')
        },
        addField (e) {
          console.log('addField')
          console.log(e)
          console.log('--------')
          this.configObj=e.configObj
          this.index=e.objCoord.index
          let id=this.configObj.id
          let width=100
          let height=10
          let x=10
          let y=10
          let name=this.index.toString()
          let fill=''
          let stroke='red'
          if (this.configObj.type== 'today'){
                  fill='blue'
          } else if (this.configObj.type== 'sign'){
                  fill='green'
                  height=20
          } else {
            if (e.objCoord.rowOption != null) {
                  width=10
                  name=name + "_" + e.objCoord.rowOption.toString()
                  fill='#00D2FF'
            }
            if (e.objCoord.colOption != null) {
                  name=name + "_" + e.objCoord.colOption.toString()
            }
          }
          if (e.objCoord.coords != null) {
            x=e.objCoord.coords.x
            y=e.objCoord.coords.y
            stroke='black'
          }
          console.log('name:' + name)
          let pos = this.rectangles.map(function(e) { return e.name; }).indexOf(name);
          for (let i=0;i<this.rectangles.length;i++) this.rectangles[i].stroke=defaultColor
          if (pos == -1 ) {
            var rect = {
                          x: x,
                          y: y,
                          width: width,
                          height: height,
                          //id: id,
                          name: name,
                          stroke: stroke,
                          strokeWidth: 2,
                          scaleX: 1,
                          scaleY: 1, 
                          draggable:true,
                          strokeScaleEnabled: false,
                          dash: defaultStroke,
                          fill: fill,
                        };

              this.rectangles.push(rect)
          } else {
            this.rectangles[pos].stroke='red'
          }
          setTimeout(function () { 
                        this.updateTransformer();
          }.bind(this), 100)

          console.log(this.rectangles)


        },
        handleMouseMove (e) {
          const mousePos = this.$refs.stage.getNode().getPointerPosition();
          const x = mousePos.x;
          const y = parseInt(mousePos.y);
          this.text=x + "," + y
        },
        handleMouseOut(e) {
          this.text=''
        },
        handleMouseDown (e) {
          if (e.target.attrs.name) {
            for (let i=0;i<this.rectangles.length;i++) this.rectangles[i].stroke=defaultColor
            let pos = this.rectangles.map(function(e) { return e.name; }).indexOf(e.target.attrs.name);
            this.indexSelected=pos
            if (pos != -1)  {
              this.rectangles[pos].stroke='red' 
              console.log('change color:'  + pos)
            }
            let arrayElement=e.target.attrs.name.split('_')
            let colOption=null 
            let rowOption=null    
            if (arrayElement.length==2) {
              rowOption=parseInt(arrayElement[1])
            } else if (arrayElement.length==3) {
              rowOption=parseInt(arrayElement[1])
              colOption=parseInt(arrayElement[2])
            }
            this.$emit('indexSelected', {
                index: isNaN(e.target.attrs.name)==true ? e.target.attrs.name: parseInt(e.target.attrs.name),
                rowOption:rowOption,
                colOption:colOption,

          })
          }

        },
        handleMouseUp (e) {

        },
        handleDragStart (e) {
        },
        handleDragEnd (e) {
          console.log(e)
          if (e.target.attrs.name) {
            let objToSend=null
            console.log("id:" + e.target.attrs.name)
            objToSend={
              id:e.target.attrs.name,
              x:parseInt(e.target.attrs.x),
              y:parseInt(e.target.attrs.y),
            }
            let pos = this.rectangles.map(function(e) { return e.name; }).indexOf(e.target.attrs.name);
            this.indexSelected=pos
            this.rectangles[pos].x=parseInt(e.target.attrs.x)
            this.rectangles[pos].y=parseInt(e.target.attrs.y)
            console.log(objToSend)
            this.$emit('getSelections',this.rectangles)
          }
        },
        handleTransformEnd(e) {
            // shape is transformed, let us save new attrs back to the node
            // find element in our state
                console.log('handleTransformedEnd')
        },
        updateTransformer() {
                //console.log('updateTransformer')
                // here we need to manually attach or detach Transformer node
                const transformerNode = this.$refs.transformer.getNode();
                const stage = transformerNode.getStage();
                const { selectedShapeName } = this;
                //console.log(transformerNode)
                const selectedNode = stage.findOne('.' + selectedShapeName);
                // do nothing if selected node is already attached
                if (selectedNode === transformerNode.node()) {
                    return;
                }
                transformerNode.keepRatio(false)
                transformerNode.rotateEnabled(false)
                transformerNode.borderEnabled(false)
                if (selectedNode) {
                    // attach to another node
                    this.disableDeleteButton=false
                    transformerNode.attachTo(selectedNode);
                } else {
                    // remove transformer
                    transformerNode.detach();
                    this.disableDeleteButton=true
                }
                transformerNode.getLayer().batchDraw();
        },



  }
};
</script>


