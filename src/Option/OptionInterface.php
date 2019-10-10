<?php


    namespace sudnonk\Rsync\Option;


    interface OptionInterface {
        public function get(): string;

        public function getParam(): ?string;

        public function hasParam(): bool;

        public static function combine(array $options): string;
    }