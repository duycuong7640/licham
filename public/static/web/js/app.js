const $ = (selector) => document.querySelector(selector);
const $$ = (selector) => document.querySelectorAll(selector);
let current = new Date();
let cursor = new Date();
const pageParams = new URLSearchParams(window.location.search);
const requestedYear = Number(pageParams.get("year"));
const requestedMonth = Number(pageParams.get("month"));

if (requestedYear >= 1900 && requestedYear <= 2050) {
  cursor = new Date(
    requestedYear,
    requestedMonth >= 1 && requestedMonth <= 12
      ? requestedMonth - 1
      : current.getMonth(),
    1,
  );
}

const lunarApprox = (date) => ({
  day: ((date.getDate() + 18) % 30) + 1,
  month: ((date.getMonth() + 10) % 12) + 1,
});
const stems = [
  "Giáp",
  "Ất",
  "Bính",
  "Đinh",
  "Mậu",
  "Kỷ",
  "Canh",
  "Tân",
  "Nhâm",
  "Quý",
];
const branches = [
  "Tý",
  "Sửu",
  "Dần",
  "Mão",
  "Thìn",
  "Tỵ",
  "Ngọ",
  "Mùi",
  "Thân",
  "Dậu",
  "Tuất",
  "Hợi",
];

const yearlyEvents = [
  { month: 1, day: "01/01", calendar: "Dương", title: "Tết Dương lịch" },
  { month: 1, day: "01/01", calendar: "Âm", title: "Tết Nguyên đán" },
  { month: 1, day: "15/01", calendar: "Âm", title: "Tết Nguyên Tiêu" },
  {
    month: 2,
    day: "03/02",
    calendar: "Dương",
    title: "Ngày thành lập Đảng Cộng sản Việt Nam",
  },
  { month: 3, day: "08/03", calendar: "Dương", title: "Ngày Quốc tế Phụ nữ" },
  { month: 3, day: "10/03", calendar: "Âm", title: "Giỗ Tổ Hùng Vương" },
  {
    month: 4,
    day: "30/04",
    calendar: "Dương",
    title: "Ngày Giải phóng miền Nam",
  },
  { month: 5, day: "01/05", calendar: "Dương", title: "Ngày Quốc tế Lao động" },
  { month: 5, day: "05/05", calendar: "Âm", title: "Tết Đoan Ngọ" },
  {
    month: 5,
    day: "19/05",
    calendar: "Dương",
    title: "Ngày sinh Chủ tịch Hồ Chí Minh",
  },
  {
    month: 6,
    day: "01/06",
    calendar: "Dương",
    title: "Ngày Quốc tế Thiếu nhi",
  },
  { month: 7, day: "15/07", calendar: "Âm", title: "Lễ Vu Lan" },
  {
    month: 7,
    day: "27/07",
    calendar: "Dương",
    title: "Ngày Thương binh – Liệt sĩ",
  },
  { month: 8, day: "15/08", calendar: "Âm", title: "Tết Trung Thu" },
  {
    month: 8,
    day: "19/08",
    calendar: "Dương",
    title: "Ngày Cách mạng Tháng Tám",
  },
  {
    month: 9,
    day: "02/09",
    calendar: "Dương",
    title: "Ngày Quốc khánh Việt Nam",
  },
  { month: 10, day: "20/10", calendar: "Dương", title: "Ngày Phụ nữ Việt Nam" },
  {
    month: 11,
    day: "20/11",
    calendar: "Dương",
    title: "Ngày Nhà giáo Việt Nam",
  },
  {
    month: 12,
    day: "22/12",
    calendar: "Dương",
    title: "Ngày thành lập Quân đội Nhân dân Việt Nam",
  },
  { month: 12, day: "23/12", calendar: "Âm", title: "Tết Ông Công Ông Táo" },
];

function eventMarkup(event) {
  return `<li><time>${event.day}</time><span><b>${event.title}</b><small class="event-calendar ${event.calendar === "Âm" ? "lunar" : ""}">${event.calendar} lịch</small></span></li>`;
}

function yearCanChi(year) {
  return `${stems[(year + 6) % 10]} ${branches[(year + 8) % 12]}`;
}

// function syncMonthControls() {
//   if ($("#monthSelect")) $("#monthSelect").value = String(cursor.getMonth());
//   if ($("#yearSelect")) $("#yearSelect").value = String(cursor.getFullYear());
// }

function moveMonth(step) {
  const target = new Date(cursor.getFullYear(), cursor.getMonth() + step, 1);
  if (target.getFullYear() < 1900 || target.getFullYear() > 2050) return;
  cursor = target;
  renderCalendar();
}

function demoDayInfo(date) {
  const seed = Math.floor(date.getTime() / 86400000);
  const good = seed % 3 !== 0;
  const firstHours =
    "Tý (23-1), Sửu (1-3), Thìn (7-9), Tỵ (9-11), Mùi (13-15), Tuất (19-21)";
  const secondHours =
    "Dần (3-5), Mão (5-7), Ngọ (11-13), Thân (15-17), Dậu (17-19), Hợi (21-23)";
  return {
    canChi: `${stems[Math.abs(seed + 8) % 10]} ${branches[Math.abs(seed + 6) % 12]}`,
    status: good ? "Ngày Hoàng đạo" : "Ngày Hắc đạo",
    hours: good ? firstHours : secondHours,
    badHours: good ? secondHours : firstHours,
    travel: good
      ? "Đường Phong: xuất hành thuận lợi, cầu tài dễ gặp may mắn"
      : "Kim Thổ: đi xa dễ chậm trễ, cầu tài chưa thuận lợi",
    suitable: good ? "Cầu tài, gặp gỡ, ký kết" : "Nên giữ việc thường nhật",
  };
}

function tooltipMarkup(date, lunar) {
  const info = demoDayInfo(date);
  return `<span class="day-tooltip" role="tooltip">
    <b>${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()} · Âm ${lunar.day}/${lunar.month}</b>
    <span><strong>Can Chi:</strong> ${info.canChi}</span>
    <span><strong>Đánh giá:</strong> ${info.status}</span>
    <span><strong>Giờ tốt:</strong> ${info.hours}</span>
    <span><strong>Phù hợp:</strong> ${info.suitable}</span>
  </span>`;
}

function renderCalendar() {
  const root = $("#calendar");
  if (!root) return;
  const year = cursor.getFullYear();
  const month = cursor.getMonth();
  const offset = (new Date(year, month, 1).getDay() + 6) % 7;
  syncMonthControls();
  $("#monthTitle").textContent = `Tháng ${month + 1} năm ${year}`;
  root.innerHTML = Array.from(
    { length: 42 },
    (_, index) => new Date(year, month, index - offset + 1),
  )
    .map((date) => {
      const lunar = lunarApprox(date);
      const dayInfo = demoDayInfo(date);
      const isOutside = date.getMonth() !== month;
      const isCurrent =
        !isOutside && date.toDateString() === current.toDateString();
      const isToday =
        !isOutside && date.toDateString() === new Date().toDateString();
      const classes = [
        "calendar-day",
        dayInfo.status.includes("Hoàng") ? "good-day" : "bad-day",
        isOutside ? "muted" : "",
        date.getDay() % 6 === 0 ? "weekend" : "",
        isCurrent ? "selected" : "",
        isToday ? "today" : "",
      ].join(" ");
      const label = `${date.getDate()} tháng ${date.getMonth() + 1}, âm lịch ${lunar.day} tháng ${lunar.month}`;
      const interaction = isOutside
        ? 'disabled aria-disabled="true"'
        : `data-date="${date.toISOString()}"`;
      const tooltip = isOutside ? "" : tooltipMarkup(date, lunar);
      return `<button class="${classes}" ${interaction} aria-label="${label}">
      <strong>${date.getDate()}</strong><small>${lunar.day === 1 ? `1/${lunar.month}` : lunar.day}</small>
      ${isToday ? "<em>Hôm nay</em>" : ""}${tooltip}
    </button>`;
    })
    .join("");
  $$("[data-date]").forEach((button) =>
    button.addEventListener("click", () => {
      if (
        window.matchMedia("(hover: none)").matches &&
        !button.classList.contains("tooltip-open")
      ) {
        $$(".tooltip-open").forEach((item) =>
          item.classList.remove("tooltip-open"),
        );
        button.classList.add("tooltip-open");
        return;
      }
      current = new Date(button.dataset.date);
      renderToday();
      //renderCalendar();
    }),
  );
  renderMonthInsights();
}

// function renderMonthInsights() {
//   const year = cursor.getFullYear();
//   const month = cursor.getMonth();
//   const monthLabel = `tháng ${month + 1} năm ${year}`;
//   if ($("#monthPageTitle")) {
//     $("#monthPageTitle").textContent = `Lịch âm ${monthLabel}`;
//   }
//   if ($("#seoMonthTitle")) {
//     $("#seoMonthTitle").textContent = `Tổng quan lịch âm ${monthLabel}`;
//   }
//   const daysInMonth = new Date(year, month + 1, 0).getDate();
//   const groups = { good: [], bad: [] };
//   for (let day = 1; day <= daysInMonth; day += 1) {
//     const date = new Date(year, month, day);
//     const key = demoDayInfo(date).status.includes("Hoàng") ? "good" : "bad";
//     groups[key].push(
//       `<a href="chi-tiet-ngay.html?date=${year}-${String(month + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}">Ngày ${day}/${month + 1}/${year}</a>`,
//     );
//   }
//   // if ($("#goodDays")) $("#goodDays").innerHTML = groups.good.join("");
//   // if ($("#badDays")) $("#badDays").innerHTML = groups.bad.join("");
//   if ($("#monthEvents")) {
//     const events = yearlyEvents.filter((event) => event.month === month + 1);
//     $("#monthEvents").innerHTML = events.length
//       ? events.map(eventMarkup).join("")
//       : '<li class="event-empty">Tháng này chưa có sự kiện nổi bật trong dữ liệu mẫu.</li>';
//   }
// }

function renderYearEvents(selectedYear) {
  const root = $("#yearEvents");
  if (!root) return;
  root.innerHTML = Array.from({ length: 12 }, (_, index) => {
    const month = index + 1;
    const events = yearlyEvents.filter((event) => event.month === month);
    return `<section class="year-event-month"><h3><a href="lich-thang.html?month=${month}&year=${selectedYear}">Tháng ${month}</a></h3><ul>${events.length ? events.map(eventMarkup).join("") : '<li class="event-empty">Chưa có sự kiện nổi bật</li>'}</ul></section>`;
  }).join("");
}

function renderYearOverview() {
  const root = $("#yearOverview");
  if (!root) return;
  const selectedYear =
    Number($("#annualYearSelect")?.value) ||
    requestedYear ||
    new Date().getFullYear();
  const canChi = yearCanChi(selectedYear);
  if ($("#yearPageTitle"))
    $("#yearPageTitle").textContent = `Lịch âm năm ${selectedYear}`;
  if ($("#yearCanChi"))
    $("#yearCanChi").textContent = `Năm ${canChi} · ${selectedYear}`;
  if ($("#yearCanChiSummary")) $("#yearCanChiSummary").textContent = canChi;
  if ($("#yearDayCount")) {
    $("#yearDayCount").textContent =
      new Date(selectedYear, 1, 29).getMonth() === 1 ? "366" : "365";
  }
  if ($("#yearSeoTitle"))
    $("#yearSeoTitle").textContent =
      `Lịch âm năm ${selectedYear} và cách tra cứu`;
  if ($("#yearOverviewLink"))
    $("#yearOverviewLink").href = `lich-nam.html?year=${selectedYear}`;
  renderYearEvents(selectedYear);
  root.innerHTML = Array.from({ length: 12 }, (_, month) => {
    const offset = (new Date(selectedYear, month, 1).getDay() + 6) % 7;
    const cells = Array.from({ length: 42 }, (_, index) => {
      const date = new Date(selectedYear, month, index - offset + 1);
      const day = date.getDate();
      const info = demoDayInfo(date);
      const lunar = lunarApprox(date);
      const lunarLabel = lunar.day === 1 ? `1/${lunar.month}` : lunar.day;
      if (date.getMonth() !== month) {
        return `<span class="mini-muted" aria-hidden="true"><strong>${day}</strong><small>${lunarLabel}</small></span>`;
      }
      return `<a class="${info.status.includes("Hoàng") ? "mini-good" : "mini-bad"}" href="chi-tiet-ngay.html?date=${selectedYear}-${String(month + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}" aria-label="Ngày ${day} tháng ${month + 1}, âm lịch ${lunar.day} tháng ${lunar.month}"><strong>${day}</strong><small${lunar.day === 1 ? ' class="lunar-first"' : ""}>${lunarLabel}</small>${tooltipMarkup(date, lunar)}</a>`;
    }).join("");
    return `<section class="mini-month"><h2><a href="lich-thang.html?month=${month + 1}&year=${selectedYear}">Tháng ${month + 1}</a></h2><div class="mini-week"><span>T2</span><span>T3</span><span>T4</span><span>T5</span><span>T6</span><span>T7</span><span>CN</span></div><div class="mini-days">${cells}</div></section>`;
  }).join("");
  $$(".mini-days > a").forEach((dayLink) => {
    dayLink.addEventListener("click", (event) => {
      if (
        window.matchMedia("(hover: none)").matches &&
        !dayLink.classList.contains("tooltip-open")
      ) {
        event.preventDefault();
        event.stopPropagation();
        $$(".tooltip-open").forEach((item) =>
          item.classList.remove("tooltip-open"),
        );
        dayLink.classList.add("tooltip-open");
      }
    });
  });
}

function renderToday() {
  const lunar = lunarApprox(current);
  const dayInfo = demoDayInfo(current);
  const formatted = new Intl.DateTimeFormat("vi-VN", {
    weekday: "long",
    day: "numeric",
    month: "long",
    year: "numeric",
  }).format(current);
  if ($("#pageDate"))
    $("#pageDate").textContent =
      formatted[0].toUpperCase() + formatted.slice(1);
  if ($("#solarDay")) $("#solarDay").textContent = current.getDate();
  if ($("#solarMeta"))
    $("#solarMeta").textContent =
      `Tháng ${current.getMonth() + 1} năm ${current.getFullYear()}`;
  if ($("#solarWeekday"))
    $("#solarWeekday").textContent = new Intl.DateTimeFormat("vi-VN", {
      weekday: "long",
    }).format(current);
  if ($("#lunarDay")) $("#lunarDay").textContent = lunar.day;
  if ($("#lunarMeta"))
    $("#lunarMeta").textContent = `Tháng ${lunar.month} năm Bính Ngọ`;
  if ($("#summarySolar"))
    $("#summarySolar").textContent =
      `${current.getDate()}-${current.getMonth() + 1}-${current.getFullYear()}`;
  if ($("#summaryLunar"))
    $("#summaryLunar").textContent =
      `${lunar.day}-${lunar.month}-${current.getFullYear()}`;
  if ($("#summaryWeekday"))
    $("#summaryWeekday").textContent = formatted
      .split(",")[0]
      .replace(/^./, (letter) => letter.toUpperCase());
  if ($("#summaryCanChi")) $("#summaryCanChi").textContent = dayInfo.canChi;
  if ($("#summaryHours")) $("#summaryHours").textContent = dayInfo.hours;
}

// function init() {
//   const footer = $(".site-footer");
//   if (footer) {
//     footer.innerHTML = `<div class="footer-inner">
//       <div class="footer-brand">
//         <a class="footer-logo" href="index.html"><span aria-hidden="true"><svg viewBox="0 0 24 24" focusable="false"><rect x="3" y="4.5" width="18" height="16" rx="2.5"></rect><path d="M7 2.5v4M17 2.5v4M3 9h18"></path><path class="footer-logo-date" d="M8 12h3v3H8zM14 12h3v3h-3zM8 17h3v2H8z"></path></svg></span><b>Lịch An Nhiên</b></a>
//         <p>Tra cứu lịch âm, lịch vạn niên và kiến thức lịch Việt rõ ràng, thuận tiện trên mọi thiết bị.</p>
//         <small class="footer-note">Lịch Việt · Múi giờ GMT+7</small>
//       </div>
//       <nav class="footer-column" aria-label="Tra cứu lịch"><h3>Tra cứu</h3><a href="index.html">Âm lịch hôm nay</a><a href="lich-thang.html">Lịch theo tháng</a><a href="lich-nam.html">Lịch theo năm</a><a href="doi-ngay.html">Đổi ngày âm dương</a></nav>
//       <nav class="footer-column" aria-label="Kiến thức lịch Việt"><h3>Khám phá</h3><a href="ngay-tot.html">Xem ngày tốt</a><a href="bai-viet.html">Bài viết</a><a href="bai-viet.html?category=tu-vi">Tử vi</a><a href="bai-viet.html?category=phong-thuy">Phong thủy</a></nav>
//     </div>
//     <div class="footer-bottom"><span>© ${new Date().getFullYear()} Lịch An Nhiên</span><span>Dữ liệu lịch pháp mang tính tham khảo</span></div>`;
//   }
//   const headerTools = $(".header-tools");
//   if (headerTools && !headerTools.querySelector(".mobile-clock")) {
//     headerTools.insertAdjacentHTML(
//       "afterbegin",
//       '<time class="mobile-clock" aria-label="Giờ hiện tại tại Việt Nam" datetime="">00:00:00</time>',
//     );
//   }
//   const menuButton = $(".menu-btn");
//   if (menuButton) {
//     menuButton.innerHTML =
//       '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M4 7h16M4 12h16M4 17h16"></path></svg>';
//     menuButton.setAttribute("aria-expanded", "false");
//   }
//   const setMenuState = (isOpen) => {
//     if (!menuButton) return;
//     menuButton.setAttribute("aria-expanded", String(isOpen));
//     menuButton.setAttribute("aria-label", isOpen ? "Đóng menu" : "Mở menu");
//     menuButton
//       .querySelector("path")
//       ?.setAttribute(
//         "d",
//         isOpen ? "M6 6l12 12M18 6 6 18" : "M4 7h16M4 12h16M4 17h16",
//       );
//   };
//   const menuYear = new Date().getFullYear();
//   const selectedMenuYear =
//     requestedYear >= menuYear && requestedYear <= 2050
//       ? requestedYear
//       : menuYear;
//   // $$("[data-month-menu]").forEach((menu) => {
//   //   menu.innerHTML = Array.from(
//   //     { length: 12 },
//   //     (_, index) =>
//   //       `<a href="lich-thang.html?month=${index + 1}&year=${selectedMenuYear}">Tháng ${index + 1}</a>`,
//   //   ).join("");
//   // });
//   // $$("[data-year-menu]").forEach((menu) => {
//   //   menu.innerHTML = Array.from(
//   //     { length: Math.max(1, 2050 - menuYear + 1) },
//   //     (_, index) => {
//   //       const year = menuYear + index;
//   //       return `<a href="lich-nam.html?year=${year}">${year}</a>`;
//   //     },
//   //   ).join("");
//   // });
//   // const monthSelect = $("#monthSelect");
//   // const yearSelect = $("#yearSelect");
//   // if (monthSelect) {
//   //   monthSelect.innerHTML = Array.from(
//   //     { length: 12 },
//   //     (_, index) => `<option value="${index}">Tháng ${index + 1}</option>`,
//   //   ).join("");
//   //   monthSelect.value = String(cursor.getMonth());
//   // }
//   // if (yearSelect) {
//   //   yearSelect.innerHTML = Array.from({ length: 151 }, (_, index) => {
//   //     const year = 1900 + index;
//   //     return `<option value="${year}">${year}</option>`;
//   //   }).join("");
//   //   yearSelect.value = String(cursor.getFullYear());
//   // }
//   // const updateCalendarFromSelects = () => {
//   //   if (!monthSelect || !yearSelect) return;
//   //   cursor = new Date(Number(yearSelect.value), Number(monthSelect.value), 1);
//   //   //renderCalendar();
//   // };
//   // monthSelect?.addEventListener("change", updateCalendarFromSelects);
//   // yearSelect?.addEventListener("change", updateCalendarFromSelects);
//   const annualYearSelect = $("#annualYearSelect");
//   if (annualYearSelect) {
//     annualYearSelect.innerHTML = Array.from(
//       { length: Math.max(1, 2050 - menuYear + 1) },
//       (_, index) => {
//         const year = menuYear + index;
//         return `<option value="${year}">${year}</option>`;
//       },
//     ).join("");
//     annualYearSelect.value = String(
//       requestedYear >= menuYear && requestedYear <= 2050
//         ? requestedYear
//         : menuYear,
//     );
//     annualYearSelect.addEventListener("change", renderYearOverview);
//   }
//   $("#prevYear")?.addEventListener("click", () => {
//     if (!annualYearSelect) return;
//     annualYearSelect.value = String(
//       Math.max(menuYear, Number(annualYearSelect.value) - 1),
//     );
//     renderYearOverview();
//   });
//   $("#nextYear")?.addEventListener("click", () => {
//     if (!annualYearSelect) return;
//     annualYearSelect.value = String(
//       Math.min(2050, Number(annualYearSelect.value) + 1),
//     );
//     renderYearOverview();
//   });
//
//   const selectedTopic = pageParams.get("category") || "all";
//   const articleItems = $$(".article-item[data-category]");
//   if (articleItems.length) {
//     const pageSize = 3;
//     const filteredArticles = [...articleItems].filter(
//       (article) =>
//         selectedTopic === "all" || article.dataset.category === selectedTopic,
//     );
//     const totalPages = Math.ceil(filteredArticles.length / pageSize);
//     const requestedPage = Math.max(1, Number(pageParams.get("page")) || 1);
//     const currentPage = Math.min(requestedPage, Math.max(1, totalPages));
//     articleItems.forEach((article) => (article.hidden = true));
//     filteredArticles
//       .slice((currentPage - 1) * pageSize, currentPage * pageSize)
//       .forEach((article) => (article.hidden = false));
//     $$("[data-topic]").forEach((topic) => {
//       topic.classList.toggle("active", topic.dataset.topic === selectedTopic);
//     });
//     if ($("#articleCount")) {
//       $("#articleCount").textContent =
//         `${filteredArticles.length} bài viết${totalPages > 1 ? ` · Trang ${currentPage}/${totalPages}` : ""}`;
//     }
//     if ($("#articleEmpty")) {
//       $("#articleEmpty").hidden = filteredArticles.length !== 0;
//     }
//     const pagination = $("#articlePagination");
//     if (pagination) {
//       const pageHref = (page) => {
//         const params = new URLSearchParams();
//         if (selectedTopic !== "all") params.set("category", selectedTopic);
//         if (page > 1) params.set("page", page);
//         const query = params.toString();
//         return `bai-viet.html${query ? `?${query}` : ""}`;
//       };
//       pagination.hidden = totalPages <= 1;
//       if (totalPages > 1) {
//         const pages = [
//           ...new Set([
//             1,
//             currentPage - 1,
//             currentPage,
//             currentPage + 1,
//             totalPages,
//           ]),
//         ]
//           .filter((page) => page >= 1 && page <= totalPages)
//           .sort((a, b) => a - b);
//         let previousPage = 0;
//         const pageLinks = pages
//           .map((page) => {
//             const separator =
//               page - previousPage > 1
//                 ? '<span class="pagination-ellipsis">…</span>'
//                 : "";
//             previousPage = page;
//             return `${separator}<a href="${pageHref(page)}"${page === currentPage ? ' class="active" aria-current="page"' : ""}>${page}</a>`;
//           })
//           .join("");
//         pagination.innerHTML = `${
//           currentPage > 1
//             ? `<a class="pagination-nav" href="${pageHref(currentPage - 1)}" aria-label="Trang trước">‹ Trước</a>`
//             : '<span class="pagination-nav disabled">‹ Trước</span>'
//         }<span class="pagination-pages">${pageLinks}</span>${
//           currentPage < totalPages
//             ? `<a class="pagination-nav" href="${pageHref(currentPage + 1)}" aria-label="Trang sau">Sau ›</a>`
//             : '<span class="pagination-nav disabled">Sau ›</span>'
//         }`;
//       }
//     }
//   }
//   // renderToday();
//   // renderCalendar();
//   renderYearOverview();
//   $("#prevMonth")?.addEventListener("click", () => {
//     moveMonth(-1);
//   });
//   $("#nextMonth")?.addEventListener("click", () => {
//     moveMonth(1);
//   });
//   $("#todayMonth")?.addEventListener("click", () => {
//     cursor = new Date();
//     current = new Date();
//     renderToday();
//     //renderCalendar();
//   });
//   $("#prevDay")?.addEventListener("click", () => {
//     current.setDate(current.getDate() - 1);
//     renderToday();
//     //renderCalendar();
//   });
//   $("#nextDay")?.addEventListener("click", () => {
//     current.setDate(current.getDate() + 1);
//     renderToday();
//     //renderCalendar();
//   });
//   menuButton?.addEventListener("click", () => {
//     const nav = $(".main-nav");
//     const isOpen = nav?.classList.toggle("open") || false;
//     setMenuState(isOpen);
//   });
//   $$(".nav-trigger").forEach((trigger) => {
//     trigger.addEventListener("click", (event) => {
//       event.stopPropagation();
//       const item = trigger.closest(".nav-item");
//       $$(".nav-item.open").forEach((openItem) => {
//         if (openItem !== item) {
//           openItem.classList.remove("open");
//           openItem
//             .querySelector(".nav-trigger")
//             ?.setAttribute("aria-expanded", "false");
//         }
//       });
//       const isOpen = item.classList.toggle("open");
//       trigger.setAttribute("aria-expanded", String(isOpen));
//     });
//   });
//   document.addEventListener("click", (event) => {
//     if (!event.target.closest(".tooltip-open")) {
//       $$(".tooltip-open").forEach((item) =>
//         item.classList.remove("tooltip-open"),
//       );
//     }
//     if (event.target.closest(".nav-item")) return;
//     $$(".nav-item.open").forEach((item) => {
//       item.classList.remove("open");
//       item
//         .querySelector(".nav-trigger")
//         ?.setAttribute("aria-expanded", "false");
//     });
//     if (!event.target.closest(".site-header")) {
//       $(".main-nav")?.classList.remove("open");
//       setMenuState(false);
//     }
//   });
//   document.addEventListener("keydown", (event) => {
//     if (event.key !== "Escape") return;
//     $$(".tooltip-open").forEach((item) =>
//       item.classList.remove("tooltip-open"),
//     );
//     $$(".nav-item.open").forEach((item) => {
//       item.classList.remove("open");
//       item
//         .querySelector(".nav-trigger")
//         ?.setAttribute("aria-expanded", "false");
//     });
//     $(".main-nav")?.classList.remove("open");
//     setMenuState(false);
//   });
//   const updateClock = () => {
//     const clock = $("#clock");
//     const mobileClocks = $$(".mobile-clock");
//     if (!clock && !mobileClocks.length) return;
//     const now = new Date();
//     const fullTime = new Intl.DateTimeFormat("vi-VN", {
//       hour: "2-digit",
//       minute: "2-digit",
//       second: "2-digit",
//       hour12: false,
//     }).format(now);
//     if (clock) {
//       clock.textContent = fullTime;
//       clock.dateTime = now.toISOString();
//     }
//     mobileClocks.forEach((mobileClock) => {
//       mobileClock.textContent = fullTime;
//       mobileClock.dateTime = now.toISOString();
//     });
//   };
//   updateClock();
//   setInterval(updateClock, 1000);
//
//   const convertType = $("#convertType");
//   const lunarDaySelect = $("#lunarInputDay");
//   const lunarMonthSelect = $("#lunarInputMonth");
//   const lunarYearSelect = $("#lunarInputYear");
//   if (lunarDaySelect) {
//     lunarDaySelect.innerHTML = Array.from(
//       { length: 30 },
//       (_, index) => `<option value="${index + 1}">${index + 1}</option>`,
//     ).join("");
//     lunarDaySelect.value = "26";
//   }
//   if (lunarMonthSelect) {
//     lunarMonthSelect.innerHTML = Array.from(
//       { length: 12 },
//       (_, index) => `<option value="${index + 1}">Tháng ${index + 1}</option>`,
//     ).join("");
//     lunarMonthSelect.value = "7";
//   }
//   if (lunarYearSelect) {
//     lunarYearSelect.innerHTML = Array.from({ length: 151 }, (_, index) => {
//       const year = 1900 + index;
//       return `<option value="${year}">${year}</option>`;
//     }).join("");
//     lunarYearSelect.value = "2026";
//   }
//   convertType?.addEventListener("change", () => {
//     const isLunarInput = convertType.value === "lunar-to-solar";
//     if ($("#solarFields")) $("#solarFields").hidden = isLunarInput;
//     if ($("#lunarFields")) $("#lunarFields").hidden = !isLunarInput;
//   });
//   $("#convertForm")?.addEventListener("submit", (event) => {
//     event.preventDefault();
//     const isLunarInput = convertType?.value === "lunar-to-solar";
//     const date = isLunarInput
//       ? new Date(
//           Number(lunarYearSelect.value),
//           Number(lunarMonthSelect.value) - 1,
//           Math.min(Number(lunarDaySelect.value), 28),
//         )
//       : new Date(`${$("#convertDate").value}T12:00:00`);
//     if (Number.isNaN(date.getTime())) return;
//     const lunar = isLunarInput
//       ? {
//           day: Number(lunarDaySelect.value),
//           month: Number(lunarMonthSelect.value),
//         }
//       : lunarApprox(date);
//     const dayInfo = demoDayInfo(date);
//     const fullDate = new Intl.DateTimeFormat("vi-VN", {
//       weekday: "long",
//       day: "numeric",
//       month: "long",
//       year: "numeric",
//     }).format(date);
//     const resultHeading = $(".convert-result-head strong");
//     if (resultHeading) {
//       resultHeading.textContent =
//         fullDate.charAt(0).toUpperCase() + fullDate.slice(1);
//     }
//     if ($("#resultSolarDay")) {
//       $("#resultSolarDay").textContent = String(date.getDate()).padStart(
//         2,
//         "0",
//       );
//     }
//     if ($("#resultSolarMeta")) {
//       $("#resultSolarMeta").textContent =
//         `Tháng ${date.getMonth() + 1} năm ${date.getFullYear()}`;
//     }
//     if ($("#resultLunarDay")) {
//       $("#resultLunarDay").textContent = String(lunar.day).padStart(2, "0");
//     }
//     if ($("#resultLunarMeta")) {
//       $("#resultLunarMeta").textContent =
//         `Tháng ${lunar.month} năm Bính Ngọ${$("#lunarLeap")?.checked ? " · Nhuận" : ""}`;
//     }
//     if ($("#resultCanChi")) {
//       $("#resultCanChi").textContent =
//         `Ngày ${dayInfo.canChi} · Tháng Bính Thân · Năm Bính Ngọ`;
//     }
//     if ($("#resultHours")) $("#resultHours").textContent = dayInfo.hours;
//     if ($("#resultBadHours")) {
//       $("#resultBadHours").textContent = dayInfo.badHours;
//     }
//     if ($("#resultTravel")) {
//       $("#resultTravel").textContent = dayInfo.travel;
//     }
//     cursor = new Date(date.getFullYear(), date.getMonth(), 1);
//     current = date;
//     //renderCalendar();
//     $("#convertResult")?.scrollIntoView({
//       behavior: "smooth",
//       block: "center",
//     });
//   });
// }
// init();


function cleanContent(root) {
    if (!root) return;

    // =========================
    // 1. XÓA <p> RỖNG NẾU CÓ
    // =========================
    root.querySelectorAll('p').forEach(p => {
        const text = p.textContent
            .replace(/\u00A0/g, '')
            .replace(/\s+/g, '');

        // Không có chữ hoặc số
        if (!/[\p{L}\p{N}]/u.test(text)) {
            p.remove();
        }
    });

    // =========================
    // 2. XỬ LÝ STRONG / SPAN
    // =========================
    root.querySelectorAll('strong').forEach(el => {

        // Nếu nằm trong strong/span khác thì bỏ qua
        // VD: <span><strong>TỐT</strong></span>
        if (el.parentElement?.closest('strong, span')) {
            return;
        }

        // Bên trong đã có <br> => không thêm
        if (el.querySelector('br')) {
            return;
        }

        /*
         * Tìm container gần nhất:
         * - Nếu nằm trong <p> thì xét từ đầu <p>
         * - Nếu không có <p> thì xét từ đầu .cleanContent
         */
        const container = el.closest('p') || root;

        // Lấy tất cả nội dung đứng trước strong/span
        const range = document.createRange();
        range.selectNodeContents(container);
        range.setEndBefore(el);

        const beforeFragment = range.cloneContents();

        const beforeText = beforeFragment.textContent
            .replace(/\u00A0/g, '')
            .replace(/\s+/g, '');

        // strong/span nằm đầu đoạn => không thêm br
        if (beforeText === '') {
            return;
        }

        // =========================
        // KIỂM TRA NGAY TRƯỚC CÓ BR
        // =========================
        let prev = el.previousSibling;

        // Bỏ qua khoảng trắng / &nbsp;
        while (
            prev &&
            prev.nodeType === Node.TEXT_NODE &&
            prev.textContent
                .replace(/\u00A0/g, '')
                .trim() === ''
            ) {
            prev = prev.previousSibling;
        }

        // Ngay trước đã có <br>
        if (
            prev &&
            prev.nodeType === Node.ELEMENT_NODE &&
            prev.tagName === 'BR'
        ) {
            return;
        }

        // =========================
        // THÊM BR
        // =========================
        el.before(document.createElement('br'));
    });
}


function runCleanContent() {
    document.querySelectorAll('.cleanContent').forEach(root => {
        cleanContent(root);
    });
}


// Chạy sau khi DOM đã sẵn sàng
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', runCleanContent);
} else {
    runCleanContent();
}

document.addEventListener('click', function (e) {

    const el = e.target.closest('a.btn-loading, button.btn-loading');

    if (!el) {
        return;
    }

    // Nếu đang loading
    if (el.classList.contains('btn-loading-active')) {
        e.preventDefault();
        e.stopImmediatePropagation();
        return;
    }

    el.classList.add('btn-loading-active');

    if (el.tagName === 'BUTTON') {
        el.disabled = true;
    }

    showGlobalLoading();

    // tự mở lại sau 3 giây
    setTimeout(function () {

        el.classList.remove('btn-loading-active');

        if (el.tagName === 'BUTTON') {
            el.disabled = false;
        }

        hideGlobalLoading();

    }, 3000);
});


function showGlobalLoading() {

    const loading = document.getElementById('global-loading');

    loading.style.display = 'block';

    // restart animation progress
    const progress = loading.querySelector('.global-loading-progress');

    progress.style.animation = 'none';

    progress.offsetHeight;

    progress.style.animation = 'loading-progress 3s linear forwards';
}


function hideGlobalLoading() {

    const loading = document.getElementById('global-loading');

    loading.style.display = 'none';
}
