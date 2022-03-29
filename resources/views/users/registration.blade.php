@extends('layouts.app')
@section('title')
    Регистрация
@endsection
@section('main')
    <div id="registration">
        <div class="container">
            <div class="registration_inner block_inner">
                <form enctype="multipart/form-data" id="registration_form" @submit.prevent="reg">
                    <div class="statuses">
                        <div> <input type="radio" name="status" class="status"  value="Исполнитель">Исполнитель

                        </div>
                        <div> <input type="radio" name="status" class="status" value="Заказчик">Заказчик
                        </div>
                    </div>
                    <input type="text" class="name" name="name" placeholder="Имя" >

                    <input type="text" name="surname" placeholder="Фамилия" class="surname"  >

                    <input type="text" name="patronymic" placeholder="Отчество" class="patronymic">

                    <input type="email" name="email" placeholder="E-mail" class="email" >

                    <input type="tel" name="phone" placeholder="Телефон" class="phone" >

                    <input type="text" name="login" placeholder="Логин" class="login_user" >

                    <input type="password" name="password" placeholder="Пароль" class="password" >

                    <input type="password" name="password_confirmation" placeholder="Подтвердите пароль" class="password_confirmation" >

                    <input type="file" name="img" class="img">

                    <input type="checkbox" name="check" class="check">Согласен с передачей персональных данных

                    <button type="submit" name="submit">Зарегистрироваться</button>
                </form>
            </div>
        </div>
    </div>
{{--    <script src="https://unpkg.com/vue@next"></script>--}}
{{--    <script>--}}
{{--        const registration ={--}}
{{--            data(){--}}
{{--                return{--}}
{{--                    error_status: '',--}}
{{--                    error_name: '',--}}
{{--                    error_surname: '',--}}
{{--                    error_patronymic: '',--}}
{{--                    error_email: '',--}}
{{--                    error_phone: '',--}}
{{--                    error_login: '',--}}
{{--                    error_password: '',--}}
{{--                    error_password_confirmation: '',--}}
{{--                    error_img: '',--}}
{{--                    error_check: ''--}}
{{--                }--}}
{{--            },--}}
{{--            methods:{--}}
{{--                async reg(){--}}
{{--                    const form = document.querySelector('#registration_form');--}}
{{--                    const form_data = new FormData(form);--}}
{{--                    const response = await fetch('{{ route('registration') }}',{--}}
{{--                        method: 'post',--}}
{{--                        headers:{--}}
{{--                            'X-CSRF-TOKEN': '{{ csrf_token() }}'--}}
{{--                        },--}}
{{--                        body:form_data--}}
{{--                    })--}}

{{--                    if (response.status === 401){--}}
{{--                        const data = await response.json()--}}
{{--                        this.error_status = data.error_status--}}
{{--                        this.error_name = data.error_name--}}
{{--                        this.error_surname = data.error_surname--}}
{{--                        this.error_patronymic = data.error_patronymic--}}
{{--                        this.error_email = data.error_email--}}
{{--                        this.error_phone = data.error_phone--}}
{{--                        this.error_login = data.error_login--}}
{{--                        this.error_password = data.error_password--}}
{{--                        this.error_password_confirmation = data.error_password_confirmation--}}
{{--                        this.error_img = data.error_img--}}
{{--                        this.error_check = data.error_check--}}
{{--                    }--}}

{{--                    if(response.status === 200 && response.redirected){--}}
{{--                        window.location = response.url--}}
{{--                    }--}}
{{--                }--}}
{{--            }--}}
{{--        }--}}
{{--        Vue.createApp(registration).mount('#registration')--}}
{{--    </script>--}}
@endsection

