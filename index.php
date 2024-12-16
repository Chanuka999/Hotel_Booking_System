<?php

include "./components/connect.php";

if(isset($_COOKIE['user_id'])){
  $user_id = $_COOKIE['user_id'];
}else{
  setcookie('user_id', create_unique_id(), time() + 60*60*24*30,'/');
  header('location:index.php');
}

if(isset($_POST["check"])){
  $check_in = $_POST["check_in"];
  $check_in = filter_var($check_in, FILTER_SANITIZE_STRING);

  $total_rooms=0;

  $check_bookings = $conn->prepare("SELECT rooms FROM `bookings` WHERE check_in=?");

  $check_bookings->bind_param("s",$check_in);
  $check_bookings->execute();

  $check_bookings->bind_result($rooms);


 while($check_bookings->fetch()){
  $total_rooms += $rooms;
 }

 $check_bookings->close();

  if($total_rooms >=30){
    $warnning_msg[] = 'rooms are not available';
  }else{
    $success_msg[] = 'rooms are available';
  }
}

if(isset($_POST["book"])){
  $book_id = create_unique_id();
  $name = $_POST["name"];
  $name = filter_var($name, FILTER_SANITIZE_STRING);
  $email = $_POST["email"];
  $email = filter_var($email, FILTER_SANITIZE_STRING);
  $number = $_POST["number"];
  $number = filter_var($number, FILTER_SANITIZE_STRING);
  $rooms = $_POST["rooms"];
  $rooms = filter_var($rooms, FILTER_SANITIZE_STRING);
  $check_in = $_POST["check_in"];
  $check_in = filter_var($check_in, FILTER_SANITIZE_STRING);
  $check_out = $_POST["check_out"];
  $check_out = filter_var($check_out, FILTER_SANITIZE_STRING);
  $adults = $_POST["adults"];
  $adults = filter_var($adults, FILTER_SANITIZE_STRING);
  $child = $_POST["child"];
  $child = filter_var($child, FILTER_SANITIZE_STRING);
  $total_rooms=0;

  $check_bookings = $conn->prepare("SELECT rooms FROM `bookings` WHERE check_in=?");

  $check_bookings->bind_param("s",$check_in);
  $check_bookings->execute();

  $check_bookings->bind_result($rooms);


 while($check_bookings->fetch()){
  $total_rooms += $rooms;
 }

 $check_bookings->close();

  if($total_rooms >=30){
    $warnning_msg[] = 'rooms are not available';
  }else{
    $verify_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE
    user_id =? AND name = ? AND email = ? AND number = ? 
    AND rooms = ? AND check_in =? AND check_out =? AND adults =? AND child=?");
   
   if($verify_bookings->num_rows()>0){
    $warnning_msg[] = 'room booked already!';
   }else{
    $book_room = $conn->prepare("INSERT INTO `bookings`(booking_id,user_id,
    name,email,number,rooms,check_in,check_out,adults,child)
    VALUES(?,?,?,?,?,?,?,?,?,?)");
    $book_room->execute([$book_id,$user_id,$name,$email,$number,$rooms,
    $check_in,$check_out,$adults,$child]);
    $success_msg = 'room added successfully';

   }
  }

}

if(isset($_POST["send"])){
  $id = create_unique_id();
  $name = $_POST["name"];
  $name = filter_var($name, FILTER_SANITIZE_STRING);
  $email = $_POST["email"];
  $email = filter_var($email, FILTER_SANITIZE_STRING);
  $password = $_POST["password"];
  $password = filter_var($password, FILTER_SANITIZE_STRING);
  $message = $_POST["message"];
  $message = filter_var($name, FILTER_SANITIZE_STRING);

  $verify_mssage = $conn->prepare("SELECT * FROM  `messages` WHERE name=? AND email= ?, AND number = ?, Add message = ? ");

  $verify_mssage->execute([$name,$email,$number,$message]);

  if($verify_mssage->num_rows() > 0){
    $warnning_msg[] = 'message sent already';
  }else{
    $insert_message = $conn->prepare("INSERT INTO `messages` (id,mame,email,number,message)
    VALUES(?,?,?,?,?.?)");
    $insert_message->execute([$id,$name,$email,$number,$message]);
    $success_msg[] = 'massage and successfully';
  }
 
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
    />
    <link rel="stylesheet" href="css/style.css" />
  </head>
  <body>
   <?php include "./components/user_header.php"; ?>

    <section class="home" id="home">
      <div class="swiper home-slider">
        <div class="swiper-wrapper">
          <div class="box swiper-slide">
            <img src="images/home-img-1.jpg" alt="" />
            <div class="flex">
              <h3>luxurious rooms</h3>
              <a ref="#" class="btn">check availability</a>
            </div>
          </div>

          <div class="box swiper-slide">
            <img src="images/home-img-2.jpg" alt="" />
            <div class="flex">
              <h3>foods and drinks</h3>
              <a ref="#" class="btn">make a reservation</a>
            </div>
          </div>

          <div class="box swiper-slide">
            <img src="images/home-img-3.jpg" alt="" />
            <div class="flex">
              <h3>luxurious halls</h3>
              <a ref="#" class="btn">contact us</a>
            </div>
          </div>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
      </div>
    </section>

    <section class="availability" id="availability">
      <form action="" method="POST">
        <div class="flex">
          <div class="box">
            <p>check in<span>*</span></p>
            <input type="date" name="check_in" class="input" required />
          </div>
          <div class="box">
            <p>check out<span>*</span></p>
            <input type="date" name="check_id" class="input" required />
          </div>
          <div class="box">
            <p>adults<span>*</span></p>
            <select name="adults" class="input" required>
              <option value="-">0 adults</option>
              <option value="1">1 adults</option>
              <option value="2">2 adults</option>
              <option value="3">3 adults</option>
              <option value="4">4 adults</option>
              <option value="5">5 adults</option>
              <option value="6">6 adults</option>
            </select>
          </div>
          <div class="box">
            <p>adults<span>*</span></p>
            <select name="child" class="input" required>
              <option value="-">0 child</option>
              <option value="1">1 child</option>
              <option value="2">2 child</option>
              <option value="3">3 child</option>
              <option value="4">4 child</option>
              <option value="5">5 child</option>
              <option value="6">6 child</option>
            </select>
          </div>
          <div class="box">
            <p>rooms<span>*</span></p>
            <select name="room" class="input" required>
              <option value="-">0 room</option>
              <option value="1">1 room</option>
              <option value="2">2 room</option>
              <option value="3">3 room</option>
              <option value="4">4 room</option>
              <option value="5">5 room</option>
              <option value="6">6 room</option>
            </select>
          </div>
        </div>
        <input
          type="submit"
          value="check availability"
          name="check"
          class="btn"
        />
      </form>
    </section>

    <section class="about" id="about">
      <div class="row">
        <div class="image">
          <img src="images/about-img-1.jpg" alt="" />
        </div>
        <div class="content">
          <h3>best staff</h3>
          <p>
            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Alias
            tempore fuga distinctio, provident reprehenderit magnam, quisquam,
          </p>
          <a href="#reservation" class="btn">make a reservation</a>
        </div>
      </div>

      <div class="row revers">
        <div class="image">
          <img src="images/about-img-2.jpg" alt="" />
        </div>
        <div class="content">
          <h3>best foods</h3>
          <p>
            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Alias
            tempore fuga distinctio, provident reprehenderit magnam, quisquam,
          </p>
          <a href="#contact" class="btn">contact</a>
        </div>
      </div>

      <div class="row">
        <div class="image">
          <img src="images/about-img-3.jpg" alt="" />
        </div>
        <div class="content">
          <h3>swimming pool</h3>
          <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Alias</p>
          <a href="#availability" class="btn">check availability</a>
        </div>
      </div>
    </section>

    <section class="services">
      <div class="box-container">
        <div class="box">
          <img src="images/icon-1.png" alt="" />
          <h3>food & drinks</h3>
          <p>
            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Doloremque
            vel quasi deserunt corporis aut nihil sunt voluptates porro modi
          </p>
        </div>
      </div>

      <div class="box-container">
        <div class="box">
          <img src="images/icon-2.png" alt="" />
          <h3>outdoor dining</h3>
          <p>
            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Doloremque
            vel quasi deserunt corporis aut nihil sunt voluptates porro modi
            culpa consequatur, magnam quia necessitatibus, voluptate maiores
            harum eaque tenetur. Porro!
          </p>
        </div>
      </div>

      <div class="box-container">
        <div class="box">
          <img src="images/icon-3.png" alt="" />
          <h3>beach view</h3>
          <p>
            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Doloremque
            vel quasi deserunt corporis aut nihil sunt voluptates porro modi
            culpa consequatur, magnam quia necessitatibus, voluptate maiores
            harum eaque tenetur. Porro!
          </p>
        </div>
      </div>

      <div class="box-container">
        <div class="box">
          <img src="images/icon-4.png" alt="" />
          <h3>decorations</h3>
          <p>
            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Doloremque
            vel quasi deserunt corporis aut nihil sunt voluptates porro modi
            culpa consequatur, magnam quia necessitatibus, voluptate maiores
            harum eaque tenetur. Porro!
          </p>
        </div>
      </div>

      <div class="box-container">
        <div class="box">
          <img src="images/icon-5.png" alt="" />
          <h3>swimming pool</h3>
          <p>
            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Doloremque
            vel quasi deserunt corporis aut nihil sunt voluptates porro modi
            culpa consequatur, magnam quia necessitatibus, voluptate maiores
            harum eaque tenetur. Porro!
          </p>
        </div>
      </div>

      <div class="box-container">
        <div class="box">
          <img src="images/icon-6.png" alt="" />
          <h3>resolt beach</h3>
          <p>
            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Doloremque
            vel quasi deserunt corporis aut nihil sunt voluptates porro modi
            culpa consequatur, magnam quia necessitatibus, voluptate maiores
            harum eaque tenetur. Porro!
          </p>
        </div>
      </div>
    </section>

    <section class="reservation" id="reservation">
      <form action="" method="POST">
        <h3>make a reservation</h3>
        <div class="flex">
        <div class="box">
            <p>name<span>*</span></p>
            <input type="text" name="name" class="input" required />
          </div>
          <div class="box">
            <p>E mail<span>*</span></p>
            <input type="text" name="email" class="input" required />
          </div>
          <div class="box">
            <p>number<span>*</span></p>
            <input type="text" name="number" class="input" required />
          </div>
          <div class="box">
            <p>rooms<span>*</span></p>
            <select name="rooms" class="input" required>
              <option value="-">0 room</option>
              <option value="1">1 room</option>
              <option value="2">2 room</option>
              <option value="3">3 room</option>
              <option value="4">4 room</option>
              <option value="5">5 room</option>
              <option value="6">6 room</option>
            </select>
          </div>
          <div class="box">
            <p>check in<span>*</span></p>
            <input type="date" name="check_in" class="input" required />
          </div>
          <div class="box">
            <p>check out<span>*</span></p>
            <input type="date" name="check_out" class="input" required />
          </div>
          <div class="box">
            <p>adults<span>*</span></p>
            <select name="adults" class="input" required>
              <option value="-">0 adults</option>
              <option value="1">1 adults</option>
              <option value="2">2 adults</option>
              <option value="3">3 adults</option>
              <option value="4">4 adults</option>
              <option value="5">5 adults</option>
              <option value="6">6 adults</option>
            </select>
          </div>
          <div class="box">
            <p>child<span>*</span></p>
            <select name="child" class="input" required>
              <option value="-">0 child</option>
              <option value="1">1 child</option>
              <option value="2">2 child</option>
              <option value="3">3 child</option>
              <option value="4">4 child</option>
              <option value="5">5 child</option>
              <option value="6">6 child</option>
            </select>
          </div>
       
        </div>
        <input
          type="submit"
          value="check availability"
          name="book"
          class="btn"
        />
      </form>
    </section>

    <section class="gallery" id="gallery">
      <div class="swiper gallery-slider">
        <div class="swiper-wrapper">
          <img src="images/gallery-img-1.jpg" class="swiper-slide" alt="" />
          <img src="images/gallery-img-2.webp" class="swiper-slide" alt="" />
          <img src="images/gallery-img-3.webp" class="swiper-slide" alt="" />
          <img src="images/gallery-img-4.webp" class="swiper-slide" alt="" />
          <img src="images/gallery-img-5.webp" class="swiper-slide" alt="" />
          <img src="images/gallery-img-6.webp" class="swiper-slide" alt="" />
        </div>
        <div class="swiper-pagination"></div>
      </div>
    </section>

    <section class="contact" id="contact">
      <div class="row">
        <form action="" method="POST">
          <h3>ask us questions</h3>
          <input
            type="text"
            name="name"
            required
            maxlength="50"
            placeholder="enter your name"
            class="box"
          />
          <input
            type="email"
            name="email"
            required
            maxlength="50"
            placeholder="enter your email"
            class="box"
          />
          <input
            type="number"
            name="number"
            required
            maxlength="10"
            min="0"
            max="9999999999"
            placeholder="enter your number"
            class="box"
          />
          <textarea
            name="msg"
            class="box"
            required
            maxlength="1000"
            placeholder="enter your msg"
            cols="30"
            rows="10"
          ></textarea>
          <input type="submit" value="send message" name="send" class="btn" />
        </form>

        <div class="faq">
          <h3 class="title">frequently asked questions</h3>
          <div class="box active">
            <h3>how to cancel?</h3>
            <p>
              Lorem, ipsum dolor sit amet consectetur adipisicing elit.
              Distinctio magnam placeat laudantium nobis quaerat molestiae?
            </p>
          </div>
          <div class="box">
            <h3>is there any vacancy?</h3>
            <p>
              Lorem, ipsum dolor sit amet consectetur adipisicing elit.
              Distinctio magnam placeat laudantium nobis quaerat molestiae?
            </p>
          </div>
          <div class="box">
            <h3>what are pament method?</h3>
            <p>
              Lorem, ipsum dolor sit amet consectetur adipisicing elit.
              Distinctio magnam placeat laudantium nobis quaerat molestiae?
            </p>
          </div>
          <div class="box">
            <h3>how to claim coupns codes?</h3>
            <p>
              Lorem, ipsum dolor sit amet consectetur adipisicing elit.
              Distinctio magnam placeat laudantium nobis quaerat molestiae?
            </p>
          </div>
          <div class="box">
            <h3>what are the age requirement?</h3>
            <p>
              Lorem, ipsum dolor sit amet consectetur adipisicing elit.
              Distinctio magnam placeat laudantium nobis quaerat molestiae?
            </p>
          </div>
        </div>
      </div>
    </section>

    <section class="reviews" id="reviews">
      <div class="swiper reviews-slider">
        <div class="swiper-wrapper">
          <div class="swiper-slide box">
            <img src="images/pic-1.png" alt="" />
            <h3>chanuka</h3>
            <p>
              Lorem, ipsum dolor sit amet consectetur adipisicing elit. Totam,
              molestiae?
            </p>
          </div>
          <div class="swiper-slide box">
            <img src="images/pic-2.png" alt="" />
            <h3>chanuka</h3>
            <p>
              Lorem, ipsum dolor sit amet consectetur adipisicing elit. Totam,
              molestiae?
            </p>
          </div>
          <div class="swiper-slide box">
            <img src="images/pic-3.png" alt="" />
            <h3>chanuka</h3>
            <p>
              Lorem, ipsum dolor sit amet consectetur adipisicing elit. Totam,
              molestiae?
            </p>
          </div>
          <div class="swiper-slide box">
            <img src="images/pic-4.png" alt="" />
            <h3>chanuka</h3>
            <p>
              Lorem, ipsum dolor sit amet consectetur adipisicing elit. Totam,
              molestiae?
            </p>
          </div>
          <div class="swiper-slide box">
            <img src="images/pic-5.png" alt="" />
            <h3>chanuka</h3>
            <p>
              Lorem, ipsum dolor sit amet consectetur adipisicing elit. Totam,
              molestiae?
            </p>
          </div>
          <div class="swiper-slide box">
            <img src="images/pic-6.png" alt="" />
            <h3>chanuka</h3>
            <p>
              Lorem, ipsum dolor sit amet consectetur adipisicing elit. Totam,
              molestiae?
            </p>
          </div>
        </div>
        <div class="swiper-pagination"></div>
      </div>
    </section>

   <?phpinclude "./components/footer.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="js/index.js"></script>
  </body>
</html>
<?php

include "./components/message.php";

?>