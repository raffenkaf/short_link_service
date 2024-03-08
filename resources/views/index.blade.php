@props(['shortLinks'])

<x-layout>
    <div class="container text-center mt-5 p-3 bg-light bg-gradient">
        <div class="row justify-content-center">
            <div class="col-10">
                <h2>
                    Будь ласка введіть той URL, який ви хочете закодувати з відповідними параметрами
                </h2>
                <form class="mt-3" action="/" method="post">

                    @csrf
                    <div class="text-start">
                        <div class="row p-3">
                            <label for="url" class="col-4">Ваш URL</label>
                            <input type="text"
                                   id="url"
                                   name="url"
                                   class="col-8"
                                   placeholder="https://example.com"
                                   required>
                        </div>
                        @error('url')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror


                        <div class="row p-3">
                            <label for="redirect_limit" class="col-4">Кількість переходів(0 - безліміт)</label>
                            <input type="number"
                                   name="redirect_limit"
                                   id="redirect_limit"
                                   class="col-8"
                                   value="0"
                                   required>
                        </div>
                        @error('redirect_limit')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <div class="row p-3">
                            <label for="expires_at" class="col-4">Час життя посилання(у секундах)</label>
                            <input type="number"
                                   name="expires_at"
                                   id="expires_at"
                                   class="col-8"
                                   value="86400"
                                   required>
                        </div>
                        @error('expires_at')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                    </div>

                    <button type="submit" class="btn btn-primary">Сгенерувати посилання</button>
                </form>
            </div>
        </div>
    </div>
    @if(count($shortLinks) > 0)
        <div class="container text-center mt-3 bg-light bg-gradient">
            <div class="row justify-content-center p-3">
                <div class="col-10">
                    <h3 class="text-center">
                        Сгенеровані посилання у зворотньому порядку
                    </h3>
                    @foreach($shortLinks as $link)
                        <div class="row p-2 {{ $loop->odd ? 'bg-light-subtle' : 'bg-body-secondary' }}">
                            <div class="col-4">
                                <p class="mb-1">Дата створення:</p>
                                <p class="mb-1">Коротке посилання:</p>
                                <p class="mb-1">Оригінальне посилання:</p>
                            </div>
                            <div class="col-8 text-start">
                                <p class="mb-1">{{$link->created_at}}</p>
                                <p class="mb-1">{{$link->formShortLinkUrl()}}</p>
                                <p class="mb-1">{{$link->url}}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</x-layout>
