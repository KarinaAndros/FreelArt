@extends('layouts.app')
@section('title')
    Main
    @endsection
@section('main')
    <div class="categories_add">
        <form class="category_add" @submit.prevent="add">
            <input type="text" name="title">
            <button type="submit">Добавить</button>
        </form>
    </div>
    <script src="https://unpkg.com/vue@next"></script>
    <script>
        const category ={
            data(){
                return{
                    error_status: '',
                    error_name: '',
                    error_surname: '',
                    error_patronymic: '',
                    error_email: '',
                    error_phone: '',
                    error_login: '',
                    error_password: '',
                    error_password_confirmation: '',
                    error_img: '',
                    error_check: ''
                }
            },
            methods:{
                async add(){
                    const form = document.querySelector('.category_add');
                    const form_data = new FormData(form);
                    const response = await fetch('{{ route('genres.store') }}',{
                        method: 'post',
                        headers:{
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body:form_data
                    })
                }
            }
        }
        Vue.createApp(category).mount('.categories_add')
    </script>
@endsection
