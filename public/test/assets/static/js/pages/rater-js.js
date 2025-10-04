raterJs({
    element: document.querySelector("#step"),
    rateCallback: function rateCallback(rating, done) {
        this.setRating(rating);
        done();
    },
    starSize: 35,
    step: 1,
});
