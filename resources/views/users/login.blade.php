@extends('layouts.app')
@section('title')
    Авторизация
@endsection
@section('main')
    <div id="login">
        <div class="container">
            <div class="registration_inner block_inner">
                <form id="login_form" @submit.prevent="log">
                    <input type="text" name="login" placeholder="Логин" class="login_user" >
                    <div class="invalid-feedback">
                        @{{ login_error }}
                    </div>
                    <input type="password" name="password" placeholder="Пароль" class="password" >
                    <div class="invalid-feedback">
                        @{{ password_error }}
                    </div>
                    <button type="submit" name="submit">Войти</button>
                </form>
            </div>
        </div>
    </div>
        <script src="https://unpkg.com/vue@next"></script>
        <script>
            const Login ={
                data(){
                    return{
                        login_error: '',
                        password_error: '',
                        message: ''
                    }
                },
                methods:{
                    async log(){
                        const form = document.querySelector('#login_form');
                        const form_data = new FormData(form);
                        const response = await fetch('{{ route('login') }}',{
                            method: 'post',
                            headers:{
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',

                            },
                            body:form_data
                        })

                        if (response.status === 400){
                            const data = await response.json()
                            this.login_error = data.login_error
                            this.password_error = data.password_error
                        }

                        if(response.status === 200 && response.redirected){
                            window.location = response.url
                            // const data = await response.json()
                            // this.message = data.name

                        }
                    }
                }
            }
            Vue.createApp(Login).mount('#login')
        </script>
@endsection

