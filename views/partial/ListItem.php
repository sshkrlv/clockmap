<?php
namespace Main;

class ListItem
{
    static function render($heading, $placeholder, $hint, $link = "#")
    {
        echo '
            <a href="' . $link . '" class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true">
                <svg  xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-clock rounded-circle flex-shrink-0 align-self-center" viewBox="0 0 16 16">
                    <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
                </svg>
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
