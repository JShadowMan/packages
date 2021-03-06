package com.wjiec.springaio.webflux.controller;

import com.wjiec.springaio.webflux.model.Item;
import org.junit.jupiter.api.Test;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.test.context.SpringBootTest;
import org.springframework.http.MediaType;
import org.springframework.test.web.reactive.server.WebTestClient;
import reactor.core.publisher.Mono;

@SpringBootTest(webEnvironment = SpringBootTest.WebEnvironment.RANDOM_PORT)
public class ItemControllerSpringTest {

    @Autowired
    private WebTestClient webTestClient;

    @Test
    public void shouldReturnItems() {
        webTestClient.get().uri("/item")
            .exchange()
            .expectStatus()
            .isOk()
            .expectBody()
            .jsonPath("$").isArray()
            .jsonPath("$").isNotEmpty()
            .jsonPath("$[0].id").isEqualTo(1L);
    }

    @Test
    public void shouldSaveItem() {
        Item item = Item.builder().title("banana").price(1.0).build();
        webTestClient.post().uri("/item")
            .contentType(MediaType.APPLICATION_JSON)
            .body(Mono.just(item), Item.class)
            .exchange()
            .expectStatus()
            .is2xxSuccessful()
            .expectBody(Item.class)
            .isEqualTo(item);
    }

}
