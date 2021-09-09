<template>
    <html>
    <head></head>
    <body>
    <div class="header">
        <div class="cont">
            <div class="navbar">
                <div class="logo">
                    <h1>Biothal</h1>
                </div>
                <div class="menu">
                    <ul>
                        <li><a href="/home">Главная</a></li>
                        <li><a href="#">О нас</a></li>
                        <li><a href="#">Контакты</a></li>
                        <li><a href="/admin/dashboard" style="border: none;">Панель управления</a></li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="foto">
        <img src="https://images.pexels.com/photos/4553036/pexels-photo-4553036.jpeg?auto=compress&cs=tinysrgb&dpr=3&h=750&w=1920" alt="">
        <div class="yazi">
            <h1 class="fotoYazi">Натуральная косметика</h1>
            <a href="/category"><span>Каталог</span></a>
        </div>
    </div>
    </body>
    </html>
</template>

<script>
export default {
name: "Navbar"
}
</script>

<style scoped>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box !important;
    font-family: Verdana, Geneva, Tahoma, sans-serif;
    color: #ffffff;
}

.cont {
    max-width: 1100px;
    margin: 0 auto;
}

.header {
    width: 100%;
    background-color: #30caf1;
    opacity: .9;
}

.navbar {
    width: 100%;
    display: flex !important;
    justify-content: space-between !important;
}

.logo h1 {
    padding: .5em;
}

ul {
    display: flex;
    list-style: none;
}

ul li a {
    text-decoration: none;
    padding: 1.7em;
    display: inline-block;
    transition: all .5s;
    position: relative;
    border-right: 1px solid #000000;
}

ul li a::before {
    content: '';
    position: absolute;
    bottom: 12px;
    left: 28px;
    width: 20%;
    height: 1px;
    background: white;
    transform: scaleX(0);
    transform-origin: left;
    transition: all .5s;
}

ul li a:hover::before {
    transform: scaleX(1);
}

ul li a:hover {
    background: #30f6cf;
}

@media (max-width:800px) {
    .navbar {
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
}

@media (max-width:640px) {
    ul li a {
        padding: .5em;
    }
    ul li a::before {
        content: '';
        width: 0;
    }
}

.foto {
    position: relative !important;
}

.foto img {
    width: 100%;
    height: 89vh;
}

.foto .yazi {
    position: absolute;
    top: 30%;
    left: 1%;
    right: 1%;
    text-align: center;
    font-size: 1.5em;
}

.yazi a {
    position: relative;
    display: inline-block;
    padding: 15px 30px;
    text-transform: uppercase;
    text-decoration: none;
    font-weight: 600;
    font-size: 20px;
    margin-top: 15px;
}

.yazi a:before {
    content: '';
    position: absolute;
    top: 6px;
    left: -2px;
    width: calc(100% + 4px);
    height: calc(100% - 12px);
    background-color: #30f6cf;
    transition: 0.5s ease-in-out;
    transform: scaleY(1);
    transition-delay: 0.5s;
}

.yazi a:hover:before {
    transform: scaleY(0);
}

.yazi a:after {
    content: '';
    position: absolute;
    left: 6px;
    top: -2px;
    height: calc(100% + 4px);
    width: calc(100% - 12px);
    background-color: #30f6cf;
    transition: 0.5s ease-in-out;
    transform: scaleX(1);
}

.yazi a:hover:after {
    transform: scaleX(0);
}

.yazi a span {
    position: relative;
    z-index: 3;
}
</style>
