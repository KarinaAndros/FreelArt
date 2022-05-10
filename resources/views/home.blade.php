@extends('layouts.app')
@section('title')
    Main
@endsection
@section('main')
    <div class="container">
        <div class="art">
            <div class="options">
                <div class="options_inner">
                    {{--brushes--}}
                    <label class="brush br" name="lab"> <input id="brush"
                                                    name="tool"
                                                    onclick="border_add(this, document.querySelector('.br'))"
                                                    type="checkbox"></label>
                    <label class="brush br2" name="lab"> <input id="brush2"
                                                     onclick="border_add(this, document.querySelector('.br2'))"
                                                     name="tool" type="checkbox">
                    </label>
                    {{--shapes--}}
                    <label class="circle" name="lab"> <input id="circle"
                                                  onclick="border_add(this, document.querySelector('.circle'))"
                                                  name="tool" type="checkbox"></label>
                    <label class="rectangle" name="lab"> <input id="rectangle" value="rectangle" onclick="border_add(this, document.querySelector('.rectangle'))" name="tool"
                                                     type="checkbox"></label>
                    {{--options--}}
                    <label class="eraser" name="lab"> <input id="eraser" onclick="border_add(this, document.querySelector('.eraser'))" name="tool" type="checkbox"></label>
                    <label class="pouring"> <input id="pouring" onclick="pouringFunction(this, document.querySelector('.pouring'))" type="checkbox"></label>
                    <label><span id="number">Size</span><input type="number" value="1" min="1" max="100"
                                                               class="size"></label>
                    <label><span id="number">Opacity</span><input type="number" value="1" min="0" max="1" step="0.1"
                                                                  class="opacity_color"></label>
                    {{--colors--}}
                    <lable class="color_option">Stroke<input type="color" class="color"></lable>
                    <lable class="color_option">Pouring<input type="color" class="color_pouring"></lable>

                    <div class="sizes_rectangle">
                        <input type="text" placeholder="W" name="width" class="width">
                        <input type="text" name="height" class="height" placeholder="H">
                    </div>
                    {{--buttons--}}
                    <div id="clear_button">
                        <button class="clear_button" onclick="clear()">Очистить поле</button>
                    </div>
                    <form class="save_art">
                        <input type="button" class="button_save" value="Сохранить рисунок" onclick="convert()">
                    </form>


                </div>
            </div>

            <div class="arts">
                <canvas id="art" width="1186px" height="812px"></canvas>
            </div>
        </div>

    </div>

    <script src="http://cdn.staticfile.org/FileSaver.js/1.3.8/FileSaver.min.js"></script>
    <script>
        let art = document.getElementById('art');
        let context = art.getContext('2d');

        let stroke_color = document.querySelector('.color');
        let pouring_color = document.querySelector('.color_pouring');
        let opacity_color = document.querySelector('.opacity_color');

        let eraser = document.getElementById('eraser');
        let brush_size = document.querySelector('.size');
        let pouring = document.querySelector('#pouring');
        let clear_button = document.querySelector('.clear_button');
        let stroke = document.getElementById('stroke');

        //brushes
        let brush1 = document.getElementById('brush');
        let brush2 = document.getElementById('brush2');

        //shapes
        let circle = document.getElementById('circle');
        let rectangle = document.getElementById('rectangle');



        let but = document.getElementsByName('lab');
        let checkboxes = document.getElementsByName('tool');
        function border_add(el, il) {
            if (el.checked === true) {
                il.classList.add('border_after');
                if (el.value === 'rectangle'){
                    document.querySelector('.sizes_rectangle').style.display = 'block';
                }
                else{
                    document.querySelector('.sizes_rectangle').style.display = 'none';
                }
            } else if (el.checked === false) {
                il.classList.remove('border_after');
                if (el.value === 'rectangle'){
                    document.querySelector('.sizes_rectangle').style.display = 'none';
                }
            }
            for (let i = 0; i<but.length; i++){
                if (il !== but[i]){
                    but[i].classList.remove('border_after');
                }
            }
            for (let i = 0; i<checkboxes.length; i++){
                if (el !== checkboxes[i]){
                    checkboxes[i].checked = false;
                }
            }

        }

        function pouringFunction(el, il){
            if ( el.checked === true){
                il.classList.add('border_after');
            }
            else{
                il.classList.remove('border_after');
            }
        }


        art.onmousedown = function (event) {
            context.beginPath();
            art.onmousemove = function (event) {

                //Point
                let x = event.offsetX;
                let y = event.offsetY;
                let rad = brush_size.value;


                if (eraser.checked) {

                    context.strokeStyle = "white";
                    context.lineTo(x, y);
                    context.stroke();
                    context.shadowOffsetX = null;
                    context.shadowOffsetY = null;
                    context.shadowBlur = null;
                    context.shadowColor = null;
                } else {
                    context.strokeStyle = stroke_color.value;
                }


                //Draw
                if (brush1.checked || brush2.checked) {
                    document.querySelector('.sizes_rectangle').style.display = 'none';
                    context.lineTo(x, y);
                    context.stroke();
                } else if (circle.checked) {

                    context.strokeStyle = stroke_color.value;
                    context.fillStyle = pouring_color.value;
                    art.onmousemove = null;
                    context.arc(x, y, rad, 0, Math.PI * 2, false);
                    context.fill();
                } else if (rectangle.checked) {
                    context.strokeStyle = stroke_color.value;
                    context.fillStyle = pouring_color.value;
                    let width = document.querySelector('.width').value;
                    let height = document.querySelector('.height').value;
                    art.onmousemove = null;
                    if (width === '' && height === '') {
                        context.fillRect(x, y, 200, 100);
                    } else {
                        context.fillRect(x, y, width, height);
                    }

                }


                //Options brushes
                context.globalAlpha = opacity_color.value; //Прозрачность
                context.lineWidth = brush_size.value; //Толщина кисти
                context.lineCap = 'round'; //Тип концов линий
                context.lineJoin = 'round'; //Тип соединения линий
                context.shadowOffsetX = null;
                context.shadowOffsetY = null;
                context.shadowBlur = null;
                context.shadowColor = null;
                // context.strokeStyle = stroke_color.value; //Цвет


                //Brush1 options
                if (brush1.checked) {
                    document.querySelector('.sizes_rectangle').style.display = 'none';
                    context.filter = 'blur(1px)'; //Растушовка
                }
                if (circle.checked) {
                    document.querySelector('.sizes_rectangle').style.display = 'none';
                }
                //Brush2 options
                if (brush2.checked) {
                    context.shadowOffsetX = 0;
                    context.shadowOffsetY = 0;
                    context.shadowBlur = 7;
                    context.shadowColor = stroke_color.value;
                }


            }
            art.onmouseup = function () {
                art.onmousemove = null;
                context.closePath();

                //Pouring
                if (pouring.checked) {
                    context.fillStyle = pouring_color.value;
                    context.fill();
                }

            }

        }


        //Clear canvas
        clear_button.onclick = function () {
            context.clearRect(0, 0, 1186, 812);
        }

        //Convert in png
        function convert() {
            html2canvas(art).then(canvas => {
                canvas.toBlob(function (blob) {
                    saveAs(blob, "art.png");
                });
            });
        }
    </script>

@endsection
