const heroBanner = [
  "./assets/images/hero-banner/banner_174651238872fbc7dec1787c3022c69ba1dea74909.jpeg",
  "./assets/images/hero-banner/banner_1744678721a99dec335b912ce96aac324dc28af413.jpeg",
  "./assets/images/hero-banner/banner_17508308664f134ab8e86af37095176c849bec515c.jpeg",
  "./assets/images/hero-banner/banner_1720490670ec6b2fdabe0ebf4a46f1ab37bc6b1d89.jpeg",
];
const mobileHeroBanner = [
  "./assets/images/hero-banner/banner_1746512388078280a2e275158330e356a3f8eb9099.jpeg",
  "./assets/images/hero-banner/banner_1744678721acad77ecd600fa8fabf86cff56f76a06.jpeg",
  "./assets/images/hero-banner/banner_175083086605b07f2fcd8a4aafef130aaf62c19a79.jpeg",
  "./assets/images/hero-banner/banner_17204906715f1155a19b84d9a35b152d258cf72919.jpeg",
];

let i = 0;

const changeBanner = () => {
  const width = window.innerWidth;
  const selectedImage = width < 1024 ? mobileHeroBanner : heroBanner;
  const bannerContainer = document.querySelector(".user-banner");
  i = (i + 1) % selectedImage.length;
  bannerContainer.style.backgroundImage = `url(${selectedImage[i]})`;
};

changeBanner();

setInterval(changeBanner, 5000);
window.addEventListener("resize", changeBanner);

const robot = document.querySelectorAll(".robot-btn");
const robotChat = document.querySelector(".robot-chat");
robot.forEach((btn, index) => {
  btn.addEventListener("click", () => {
    if (index === 0) robotChat.style.display = "flex";
    else if (index === 1) robotChat.style.display = "none";
  });
});

const aiMsgSend = document.querySelector(".ai-msg-send");
const aiInput = document.querySelector(".ai-input");
const aiConvo = document.querySelector(".ai-convo");
aiMsgSend.addEventListener("click", async () => {
  const budget = aiInput.value;

  aiConvo.innerHTML += `
  <span class="flex rounded-[10px] bg-[rgb(10,10,10)] p-[12px] self-end">${budget}</span>`;

  aiInput.value = "";

  const res = await fetch("aiProductHandler.php", {
    method: "POST",
    headers: { "Content-type": "application/x-www-form-urlencoded" },
    body: `budget=${budget}`,
  });
  const data = await res.json();

  aiConvo.innerHTML += `
<span class="flex rounded-[10px] bg-[rgb(100,0,0)] p-[12px]">${data.message}</span>
<a href="${data.link}" class="underline ${data.display} rounded-[10px] bg-[rgb(100,0,0)] p-[12px]">${data.name}</a>`;
});
