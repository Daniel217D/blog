export default () => {
    Array.from(document.querySelectorAll('[data-draggable]')).forEach(draggableContainer => {
        const draggableElements = Array.from(draggableContainer.children);

        draggableElements.forEach(de => de.draggable = true);

        draggableContainer.addEventListener(`dragstart`, (e) => {
            e.target.classList.add(`selected`);
        })

        draggableContainer.addEventListener(`dragend`, (e) => {
            e.target.classList.remove(`selected`);
        });

        draggableContainer.addEventListener(`dragover`, (e) => {
            e.preventDefault();

            const activeElement = draggableContainer.querySelector(`.selected`);
            const currentElement = e.target.closest('[draggable]');

            if (!currentElement || activeElement === currentElement) {
                return;
            }

            draggableContainer.insertBefore(
                activeElement,
                currentElement === activeElement.nextElementSibling ?
                    currentElement.nextElementSibling :
                    currentElement
            );
        });
    })
}