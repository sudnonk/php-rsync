<?php


    namespace sudnonk\Rsync\Option;


    interface OptionsInterface {
        public function set(string $option, string $param): void;

        public function sets(string ...$options): void;

        public function get(): array;

        public function count(): int;
    }