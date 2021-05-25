import PDFDocument from 'pdfkit/js/pdfkit.standalone'
import base64 from './_base64ToArrayBuffer'

export default {
    createDynacert: async function (configPdf, imagePdf, pdfData, surveyConfig, arrayGraphicSign, defaultSignWidth, defaultSignHeight) {
        console.log('-----------------------------------------------------------------')
        console.log(configPdf)
        console.log(imagePdf)
        console.log(pdfData)
        console.log(surveyConfig)
        console.log(arrayGraphicSign)
        console.log('-----------------------------------------------------------------')

        let doc = null
        var d = new Date();
        var dd = String(d.getDate()).padStart(2, '0');
        var mm = String(d.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = d.getFullYear();
        const pdfChunks = [];
        var pdfBuffer = null

        for (let page = 0; page < configPdf.pages.length; page++) {
            let objPage = configPdf.pages[page]
            if (page == 0) {
                doc = new PDFDocument(
                    {
                        autoFirstPage: true,
                        size: 'A4',
                        layout: 'portrait',
                        bufferPages: true,
                    }

                )
            } else {
                doc.addPage()
            }
            if (page < imagePdf.length)
                doc.image(imagePdf[page], 0, 0, { width: 580 });
            if (page == 0)
                doc.fontSize(5).text(d, 5, 5)
            var wPre = 0;
            var xPre = -1;
            var yPre = -1
            for (let i = 0; i < pdfData.length; i++) {
                let objData = pdfData[i]
                let id = surveyConfig[i].id
                console.log(i + '<--->' + id)
                //if (i < coordsKey.length) {
                if (objPage.coords[id]) {
                    if (objPage.coords[id].type == 'simpletext'
                        || objPage.coords[id].type == 'date'
                        || objPage.coords[id].type == 'email'
                        || objPage.coords[id].type == 'tel'
                    ) {
                        console.log(objPage.coords[id])
                        let objText = objPage.coords[id]
                        if (objText.append == false) {
                            doc.font(objText.font)
                                .fontSize(objText.fontSize)
                                .text(objData, objText.x, objText.y, { align: objText.align })
                        } else {
                            doc.font(objText.font)
                                .fontSize(objText.fontSize)
                                .text(objData, xPre + wPre + 5, yPre, { align: objText.align })
                        }
                        wPre = doc.widthOfString(objData)
                        xPre = objText.x
                        yPre = objText.y
                    } else if (objPage.coords[id].type == 'multiplegrid') {
                        console.log('multiplegrid')
                        let objMultigrid = objPage.coords[id].coord
                        for (let k = 0; k < objData.length; k++) {
                            let coordSearch = k + '_' + objData[k]
                            let pos = objMultigrid.map(function (e) { return e.coord; }).indexOf(k + '_' + objData[k]);
                            console.log(coordSearch + '------>' + pos)
                            if (pos != -1) {
                                console.log(objMultigrid[pos])
                            }
                            doc.font(objMultigrid[pos].font)
                                .fontSize(objMultigrid[pos].fontSize)
                                .text(objMultigrid[pos].checkChar, objMultigrid[pos].x, objMultigrid[pos].y, { align: objMultigrid[pos].align })


                        }
                    }
                }
            }
            //sign document
            console.log('start apply')
            for (let i = 0; i < objPage.signElements.length; i++) {
                let objSign = objPage.signElements[i]
                var imageSign = null
                imageSign = base64._base64ToArrayBuffer(arrayGraphicSign[objSign.id])
                let widthSign = defaultSignWidth
                let heightSign = defaultSignHeight
                if (objSign.width) widthSign
                if (objSign.height) heightSign
                doc.image(imageSign, objSign.x, objSign.y, { fit: [widthSign, heightSign] })
            }
            for (let i = 0; i < objPage.todayElements.length; i++) {
                let objToday = objPage.todayElements[i]
                doc.font(objToday.font)
                    .fontSize(objToday.fontSize)
                    .text(dd + '/' + mm + '/' + yyyy, objToday.x, objToday.y, { align: objToday.align })
            }
            //sign document
        }

        doc.on('data', (data) => {
            pdfChunks.push(data);
        });
        doc.on('end', () => {
            console.log('end')
            pdfBuffer = Buffer.concat(pdfChunks)
            console.log(pdfBuffer)
            /*setTimeout(function () {
                //this.bufferStreamPdf = pdfBuffer
                //this.sendPdf(pdfBuffer)
            }.bind(this), 100) */

        })
        // Finalize PDF file
        doc.end();
        await new Promise(r => setTimeout(r, 1000));
        return pdfBuffer
    }
}
