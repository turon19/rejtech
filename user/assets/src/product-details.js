const productDetailsImg = document.querySelectorAll(".user-pd-img");
const productDetailsImgContainer = document.querySelector(".user-pd-img-c1");

productDetailsImgContainer.src = productDetailsImg[0].src;

productDetailsImg.forEach((image) => {
  image.addEventListener("click", () => {
    productDetailsImg.forEach((image) => image.classList.remove("active"));
    image.classList.add("active");

    productDetailsImgContainer.src = image.src;
  });
});
