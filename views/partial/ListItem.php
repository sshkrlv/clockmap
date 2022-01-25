<?php
namespace Main;

class ListItem
{
    static function render($heading, $placeholder, $hint, $link = "#")
    {
        echo '
            <a href="' . $link . '" class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true">
                <img src="https://github.com/twbs.png" alt="twbs" width="32" height="32" class="rounded-circle flex-shrink-0">
                <div class="d-flex gap-2 w-100 justify-content-between">
                    <div>
                        <h6 class="mb-0">' . $heading . '</h6>
                        <p class="mb-0 opacity-75">' . $placeholder . '</p>
                    </div>
                    <small class="opacity-50 text-nowrap">' . $hint . '</small>
                </div>
            </a>';
    }
}
