@extends('template.tsd')

<header>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="col-12">
                    <h1 class="fs-4">Поиск партии</h1>
                </div>
            </div>
        </div>
    </div>
</header>

<section class="search__form">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <form>
                    <div class="mb-3">
                        <label for="partNumber" class="form-label">Номер партии</label>
                        <input type="text" name="code" class="form-control" id="partNumber" aria-describedby="partNumber">
                        <div id="emailHelp" class="form-text">Ввод номера партии</div>
                        <div class="spinner hidden"></div>
                    </div>
                    <button type="submit" class="btn btn-primary large_btn">OK</button>
                </form>
                <button class="btn btn-primary large_btn btn_reset">Отмена</button>
            </div>
        </div>
    </div>
</section>

<section class="search__results hidden">
    <div class="container">
        <div class="row">
            <div class="col-12 search_res fs-4"></div>
        </div>
        <div class="row">
            <div class="col-12 search_res">
                <button class="btn btn-primary large_btn return_search">К поиску</button>
            </div>
        </div>
    </div>
</section>
