(() => {
    const modalEl = document.querySelector("#quick-search");
    if (!modalEl) return;

    const modal = tailwind.Modal.getOrCreateInstance(modalEl);
    const $modal = $(modalEl);
    const $input = $modal.find("input").first();
    const $globalSearch = $(".global-search").first();
    const $keyword = $(".global-search__keyword").first();

    document.addEventListener("keydown", function (e) {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === "k") {
            e.preventDefault();
            modal.show();
        }
    });

    $modal.on("shown.tw.modal", function () {
        if ($input.length) {
            $input[0].focus();
        }
    });

    if ($input.length) {
        $input.on("input", function (e) {
            const value = e.target.value || "";

            $keyword.html(`"${value}"`);

            if (value.length > 3) {
                $globalSearch.removeClass("global-search--show-result");
            } else {
                $globalSearch.addClass("global-search--show-result");
            }
        });
    }
})();
