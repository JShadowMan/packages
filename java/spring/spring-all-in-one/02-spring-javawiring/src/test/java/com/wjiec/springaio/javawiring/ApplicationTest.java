package com.wjiec.springaio.javawiring;

import com.wjiec.springaio.javawiring.model.Address;
import com.wjiec.springaio.javawiring.model.User;
import org.junit.jupiter.api.Test;
import org.junit.jupiter.api.extension.ExtendWith;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.test.context.ContextConfiguration;
import org.springframework.test.context.junit.jupiter.SpringExtension;

import static org.junit.jupiter.api.Assertions.assertEquals;
import static org.junit.jupiter.api.Assertions.assertNotNull;

@ExtendWith(SpringExtension.class)
@ContextConfiguration(classes = Application.class)
class ApplicationTest {

    @Test
    void contextLoaded() {}

    @Autowired
    private Address address;

    @Test
    void addressShouldNotBeNull() {
        assertNotNull(address);
    }

    @Autowired
    private User user;

    @Test
    void userShouldNotBeNull() {
        assertNotNull(user);
    }

    @Test
    void addressShouldBeEquals() {
        assertEquals(address, user.getAddress());
    }

}
