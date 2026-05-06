// Получаем все счетчики на странице
const counters = document.querySelectorAll(".counter");

// Для каждого счетчика создаем отдельный обработчик
counters.forEach((counter) => {
  const decreaseBtn = counter.querySelector(".decrease");
  const increaseBtn = counter.querySelector(".increase");
  const numberElement = counter.querySelector(".number");

  // У каждого счетчика своя переменная count
  let count = 1;

  decreaseBtn.addEventListener("click", function () {
    if (count > 1) {
      count--;
      numberElement.innerText = count;
    }
  });

  increaseBtn.addEventListener("click", function () {
    count++;
    numberElement.innerText = count;
  });
});
