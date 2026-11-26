@extends('layouts.app')

<style>

    .welcome-container {
        width: 100%;
        max-width: 1100px;
        min-height: 550px;
        display: flex;
        margin: 0px auto;
        align-items: center;
        justify-content: space-between;
        gap: 40px;
        padding: 65px;
        border: 1px solid #263354;
        border-radius: 25px;
        background: linear-gradient(135deg, #151e38, #10172c);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
    }

    .welcome-content {
        flex: 1;
    }

    .tag {
        display: inline-block;
        padding: 9px 18px;
        background: rgba(99, 102, 241, 0.15);
        border: 1px solid #6366f1;
        border-radius: 30px;
        color: #a5b4fc;
        font-size: 13px;
        letter-spacing: 2px;
        margin-bottom: 25px;
    }

    h1 {
        font-size: clamp(40px, 6vw, 65px);
        line-height: 1.1;
        margin-bottom: 25px;
    }

    h1 span {
        color: #818cf8;
    }

    .description {
        color: #b6c0d5;
        font-size: 17px;
        line-height: 1.8;
        max-width: 480px;
        margin-bottom: 35px;
    }

    .welcome-btn {
        display: inline-block;
        padding: 15px 32px;
        background: linear-gradient(90deg, #6366f1, #8b5cf6);
        color: white;
        text-decoration: none;
        border-radius: 10px;
        font-weight: bold;
        transition: 0.3s;
    }

    .welcome-btn:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(99, 102, 241, 0.4);
    }

    .crew-visual {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .crew-circle {
        width: 320px;
        height: 320px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #a855f7);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 125px;
        box-shadow: 0 0 80px rgba(129, 140, 248, 0.25);
    }

    .bottom-text {
        margin-top: 35px;
        color: #71809d;
        font-size: 13px;
    }

    @media (max-width: 768px) {
        .welcome-container {
            flex-direction: column;
            text-align: center;
            padding: 40px 25px;
        }
        .description {
            margin-left: auto;
            margin-right: auto;
        }
        .crew-circle {
            width: 220px;
            height: 220px;
            font-size: 90px;
        }
    }
    
    .welcome-content h1 {
        color: #fff;
    }
</style>

@section('content')

<section class="welcome-container">

    <div class="welcome-content">
        <div class="tag">✦ YOU BELONG HERE</div>

        <h1>
            Welcome to<br> Our <span>Crew!</span>
        </h1>

        <p class="description">
            We're excited to have you on board! Our crew is more than just a team — it's a community of passionate people working, growing, and achieving together. Let's make something amazing!
        </p>

        <a href="/dashboard" class="welcome-btn">
        Explore Your Crew →
      </a>

        <div class="bottom-text">
            Together We Grow · Together We Achieve
        </div>
    </div>

    <div class="crew-visual">
        <div class="crew-circle">
            👥
        </div>
    </div>

</section>

@endsection