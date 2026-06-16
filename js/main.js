document.addEventListener("DOMContentLoaded", () => {
  document.addEventListener("mousemove", (event) => {
    document.documentElement.style.setProperty("--mouse-x", `${event.pageX}px`);
    document.documentElement.style.setProperty("--mouse-y", `${event.pageY}px`);
  });
});
